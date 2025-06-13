<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Pendaftar;
use App\Models\Lowongan;
use App\Models\DokumenPendaftar;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PendaftarController extends Controller
{
    public function showPendaftaranTable(Request $request)
    {
        $userId = Auth::id();

        $pendaftarans = Pendaftar::with(['lowongan.company', 'dokumenPendaftars'])
            ->where('user_id', $userId)
            ->whereHas('user', function($query) use ($userId) {
                $query->where('id', $userId);
            })
            ->latest()
            ->paginate(10);

        return view('mahasiswa.pendaftar', [
            'pendaftarans' => $pendaftarans
        ]);
    }

    public function showPendaftaranForm(Request $request)
    {
        $userId = Auth::id();

        // Check if user already has a pending application for recommended job
        if (session('recommended_job_id')) {
            $existing = Pendaftar::where('user_id', $userId)
                ->whereHas('lowongan', function($q) {
                    $q->where('judul', 'like', '%' . session('recommended_job') . '%');
                })
                ->where('status_lamaran', 'Pending')
                ->exists();

            if ($existing) {
                return redirect()->route('mahasiswa.pendaftar')
                    ->with('error', 'Anda sudah memiliki pendaftaran aktif untuk rekomendasi ini');
            }
        }

        $lowongans = Lowongan::with('company')
            ->where('status', 'Aktif')
            ->where('tanggal_tutup', '>=', Carbon::now())
            ->get();

        $prefilledLowonganId = $request->query('lowongan_id');

        return view('mahasiswa.pendaftar-form', [
            'lowongans' => $lowongans,
            'selectedLowonganId' => $prefilledLowonganId
        ]);
    }

    public function submitPendaftaran(Request $request)
    {
        // ===== LANGKAH 1.1: TAMBAHKAN VALIDASI UNTUK RIWAYAT HIDUP =====
        $validatedData = $request->validate([
            'lowongan_id' => 'required|exists:lowongans,id',
            'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'riwayat_hidup' => 'required|file|mimes:pdf,doc,docx|max:5120', // Ditambahkan
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'khs_transkrip' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ktm' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'surat_izin_ortu' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'pakta_integritas' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'sertifikat_kompetensi' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'sktm_kip' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'catatan_pendaftar' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ], [
            'required' => 'Dokumen :attribute wajib diunggah',
            'mimes' => 'Format file :attribute tidak valid',
            'max' => 'Ukuran file :attribute maksimal :max KB',
            'terms.accepted' => 'Anda harus menyetujui persyaratan'
        ]);

        DB::beginTransaction();

        try {
            $userId = Auth::id();
            if (!$userId) {
                throw new \Exception('Anda harus login terlebih dahulu');
            }

            $existingApplication = Pendaftar::where('user_id', $userId)
                ->where('lowongan_id', $validatedData['lowongan_id'])
                ->exists();

            if ($existingApplication) {
                throw new \Exception('Anda sudah mendaftar untuk lowongan ini');
            }

            $pendaftar = Pendaftar::create([
                'user_id' => $userId,
                'lowongan_id' => $validatedData['lowongan_id'],
                'tanggal_daftar' => Carbon::now(),
                'status_lamaran' => 'Pending',
                'catatan_pendaftar' => $validatedData['catatan_pendaftar'] ?? null,
                'catatan_admin' => null,
            ]);

            // ===== LANGKAH 1.2: PISAHKAN KONFIGURASI DOKUMEN =====
            $documentConfig = [
                'surat_lamaran' => [
                    'name' => 'Surat Lamaran',
                    'required' => true,
                    'type' => 'document'
                ],
                'cv' => [
                    'name' => 'CV',
                    'required' => true,
                    'type' => 'document'
                ],
                'riwayat_hidup' => [ // Entri terpisah untuk Riwayat Hidup
                    'name' => 'Daftar Riwayat Hidup',
                    'required' => true,
                    'type' => 'document'
                ],
                'portofolio' => [
                    'name' => 'Portofolio',
                    'required' => false,
                    'type' => 'document'
                ],
                'khs_transkrip' => [
                    'name' => 'KHS atau Transkrip Nilai',
                    'required' => true,
                    'type' => 'document'
                ],
                'ktp' => [
                    'name' => 'KTP',
                    'required' => true,
                    'type' => 'image'
                ],
                'ktm' => [
                    'name' => 'KTM',
                    'required' => true,
                    'type' => 'image'
                ],
                'surat_izin_ortu' => [
                    'name' => 'Surat Izin Orang Tua',
                    'required' => true,
                    'type' => 'document'
                ],
                'pakta_integritas' => [
                    'name' => 'Pakta Integritas',
                    'required' => true,
                    'type' => 'document'
                ],
                'sertifikat_kompetensi' => [
                    'name' => 'Sertifikat Kompetensi',
                    'required' => false,
                    'type' => 'document'
                ],
                'sktm_kip' => [
                    'name' => 'SKTM atau KIP Kuliah',
                    'required' => false,
                    'type' => 'document'
                ]
            ];

            foreach ($documentConfig as $field => $config) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $originalExtension = $file->getClientOriginalExtension();
                    $extension = strtolower($originalExtension);

                    $allowedExtensions = $config['type'] === 'image'
                        ? ['jpg', 'jpeg', 'png']
                        : ['pdf', 'doc', 'docx'];

                    if (!in_array($extension, $allowedExtensions)) {
                        throw new \Exception("Format file tidak valid untuk {$config['name']}");
                    }

                    $fileName = Str::slug($config['name']).'_'.time().'_'.Str::random(6).'.'.$extension;
                    $path = $file->storeAs("dokumen_pendaftar/{$pendaftar->id}", $fileName, 'public');

                    DokumenPendaftar::create([
                        'pendaftar_id' => $pendaftar->id,
                        'nama_dokumen' => $config['name'],
                        'file_path' => $path,
                        'tipe_file' => $extension,
                        'status_validasi' => 'Belum Diverifikasi'
                    ]);

                } elseif ($config['required']) {
                    throw new \Exception("Dokumen {$config['name']} wajib diunggah");
                }
            }

            DB::commit();

            return redirect()
                ->route('mahasiswa.pendaftar')
                ->with('success', 'Pendaftaran berhasil dikirim. Dokumen Anda sedang diverifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pendaftaran Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengirim pendaftaran: ' . $e->getMessage());
        }
    }

    public function cancelPendaftaran(Pendaftar $pendaftar)
    {
        if ($pendaftar->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pendaftar->status_lamaran !== 'Pending') {
            return redirect()
                ->back()
                ->with('error', 'Hanya pendaftaran dengan status Pending yang dapat dibatalkan');
        }

        DB::beginTransaction();

        try {
            foreach ($pendaftar->dokumenPendaftars as $document) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $document->delete();
            }

            $pendaftar->delete();

            DB::commit();

            return redirect()
                ->route('mahasiswa.pendaftar')
                ->with('success', 'Pendaftaran berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pembatalan Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal membatalkan pendaftaran');
        }
    }

    public function showDocuments($pendaftarId)
    {
        $userId = Auth::id();

        $pendaftar = Pendaftar::with(['dokumenPendaftars' => function($query) use ($userId) {
            $query->whereHas('pendaftar', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->orderBy('created_at');
        }])
            ->where('user_id', $userId)
            ->findOrFail($pendaftarId);

        return view('mahasiswa.dokumen_pendaftar', [
            'dokumen' => $pendaftar->dokumenPendaftars,
            'pendaftar' => $pendaftar
        ]);
    }

    public function applyFromLowongan($lowonganId)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $existing = Pendaftar::where('user_id', $userId)
            ->where('lowongan_id', $lowonganId)
            ->exists();

        if ($existing) {
            return redirect()
                ->route('mahasiswa.pendaftar')
                ->with('error', 'Anda sudah mendaftar untuk lowongan ini');
        }

        return redirect()
            ->route('mahasiswa.pendaftar.form', ['lowongan_id' => $lowonganId]);
    }

    protected function getValidationStatusBadge($status)
    {
        switch ($status) {
            case 'Valid':
                return 'bg-green-100 text-green-700';
            case 'Tidak Valid':
                return 'bg-red-100 text-red-700';
            case 'Belum Diverifikasi':
            default:
                return 'bg-yellow-100 text-yellow-700';
        }
    }
}