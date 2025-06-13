<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenPendaftar;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PendaftarController extends Controller
{
    private $dokumenWajibNames = [
        'Surat Lamaran',
        'CV',
        'Portofolio',
        'Daftar Riwayat Hidup',
        'KHS atau Transkrip Nilai',
        'KTP',
        'KTM',
        'Surat Izin Orang Tua',
        'Pakta Integritas',
    ];

    private function getPredefinedDokumenTypesForStorage(): array
    {
        return [
            'surat_lamaran_path' => 'Surat Lamaran',
            'cv_path' => 'CV',
            'portofolio_path' => 'Portofolio',
            'daftar_riwayat_hidup_path' => 'Daftar Riwayat Hidup',
            'khs_transkrip_nilai_path' => 'KHS atau Transkrip Nilai',
            'ktp_path' => 'KTP',
            'ktm_path' => 'KTM',
            'surat_izin_orang_tua_path' => 'Surat Izin Orang Tua',
            'pakta_integritas_path' => 'Pakta Integritas',
        ];
    }

    public function index(Request $request)
    {
        $query = Pendaftar::with(['user', 'lowongan.company', 'dokumenPendaftars']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($uq) use ($searchTerm) {
                    $uq->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('lowongan', function ($lq) use ($searchTerm) {
                        $lq->where('judul', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('lowongan.company', function ($cq) use ($searchTerm) {
                        $cq->where('nama_perusahaan', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // PERUBAHAN DI SINI: Mengurutkan berdasarkan updated_at terbaru
        $pendaftars = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();

        foreach ($pendaftars as $pendaftar) {
            $semuaDokumenWajibValid = true;
            if (! empty($this->dokumenWajibNames)) {
                foreach ($this->dokumenWajibNames as $namaDocWajib) {
                    $dokumenPendaftarValid = $pendaftar->dokumenPendaftars
                        ->where('nama_dokumen', $namaDocWajib)
                        ->where('status_validasi', 'Valid')
                        ->isNotEmpty();

                    if (! $dokumenPendaftarValid) {
                        $semuaDokumenWajibValid = false;
                        break;
                    }
                }
            }
            $pendaftar->status_kelengkapan_dokumen = $semuaDokumenWajibValid ? 'Validate' : 'Invalidate';
        }

        $dokumenWajibGlobal = $this->dokumenWajibNames;

        return view('admin.Company.pendaftar', compact('pendaftars', 'dokumenWajibGlobal'));
    }

    public function create()
    {
        $mahasiswas = User::whereHas('role', function ($query) {
            $query->where('name', 'mahasiswa');
        })->orderBy('name')->get();

        $lowongans = Lowongan::where('status', 'Aktif')
            ->with('company')
            ->orderBy('judul')->get();

        return view('admin.pendaftar.create', compact('mahasiswas', 'lowongans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'exists:users,id', Rule::unique('pendaftars')->where(function ($query) use ($request) {
                return $query->where('user_id', $request->user_id)
                    ->where('lowongan_id', $request->lowongan_id);
            })],
            'lowongan_id' => 'required|exists:lowongans,id',
            'tanggal_daftar' => 'required|date',
            'status_lamaran' => 'required|in:Pending,Ditinjau,Wawancara,Diterima,Ditolak',
            'catatan_admin' => 'nullable|string',
        ], [
            'user_id.unique' => 'Mahasiswa ini sudah terdaftar pada lowongan yang sama.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.create')
                ->withErrors($validator)
                ->withInput();
        }

        $pendaftar = Pendaftar::create($request->only(['user_id', 'lowongan_id', 'tanggal_daftar', 'status_lamaran', 'catatan_admin']));

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil ditambahkan.');
    }

    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']);

        return view('admin.pendaftar.show', compact('pendaftar'));
    }

    public function edit(Pendaftar $pendaftar)
    {
        $pendaftar->load('user', 'lowongan.company');

        return view('admin.pendaftar.edit', compact('pendaftar'));
    }

    public function update(Request $request, Pendaftar $pendaftar)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_daftar' => 'required|date',
            'status_lamaran' => 'required|in:Pending,Ditinjau,Wawancara,Diterima,Ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.edit', $pendaftar->id)
                ->withErrors($validator)
                ->withInput();
        }

        $newStatusLamaran = $request->status_lamaran;
        $oldStatusLamaran = $pendaftar->status_lamaran;

        if (! in_array($newStatusLamaran, ['Pending', 'Ditolak'])) {
            $semuaDokumenWajibValid = true;
            $dokumenBelumValidInfo = [];

            foreach ($this->dokumenWajibNames as $namaDocWajib) {
                $doc = $pendaftar->dokumenPendaftars()->where('nama_dokumen', $namaDocWajib)->first();
                if (! $doc || $doc->status_validasi !== 'Valid') {
                    $semuaDokumenWajibValid = false;
                    $dokumenBelumValidInfo[] = $namaDocWajib.($doc ? ' (Status: '.$doc->status_validasi.')' : ' (Belum diunggah)');
                }
            }

            if (! $semuaDokumenWajibValid) {
                $pesanError = 'Tidak dapat mengubah status lamaran ke "'.$newStatusLamaran.'". Dokumen berikut belum valid atau belum diunggah: '.implode(', ', $dokumenBelumValidInfo).'. Harap validasi dokumen terlebih dahulu atau set status ke "Pending".';

                return redirect()->route('admin.pendaftar.edit', $pendaftar->id)
                    ->with('error', $pesanError)
                    ->withInput();
            }
        }

        $pendaftar->update($request->only(['tanggal_daftar', 'status_lamaran', 'catatan_admin']));

        if ($oldStatusLamaran !== $newStatusLamaran || $newStatusLamaran === 'Pending') {
            $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());
        }

        return redirect()->route('admin.pendaftar.index')->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function destroy(Pendaftar $pendaftar)
    {
        foreach ($pendaftar->dokumenPendaftars as $dokumen) {
            if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
        }
        $pendaftar->delete();

        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }

    public function showDokumen(Pendaftar $pendaftar)
    {
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']);

        $predefinedDokumenTypesForView = [];
        $storageDokumenTypes = $this->getPredefinedDokumenTypesForStorage();

        foreach ($storageDokumenTypes as $key => $namaDokumenStandar) {
            $label = $namaDokumenStandar;
            $opsionalKeywords = ['sertifikat kompetensi', 'surat balasan', 'bpjs atau asuransi lain', 'sktm atau kip kuliah'];
            $isOptional = false;
            foreach ($opsionalKeywords as $keyword) {
                if (stripos($namaDokumenStandar, $keyword) !== false) {
                    $isOptional = true;
                    break;
                }
            }
            if ($isOptional) {
                $label .= ' (jika ada)';
            }
            $predefinedDokumenTypesForView[$key] = $label;
        }

        return view('admin.pendaftar.show_dokumen', compact('pendaftar', 'predefinedDokumenTypesForView'));
    }

    public function uploadDokumenBatch(Request $request, Pendaftar $pendaftar)
    {
        $predefinedDokumenTypesOnStore = $this->getPredefinedDokumenTypesForStorage();

        $rules = [];
        foreach (array_keys($predefinedDokumenTypesOnStore) as $key) {
            $rules["dokumen[{$key}]"] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120';
        }

        $validator = Validator::make($request->all(), $rules, [
            'dokumen.*.mimes' => 'Format file tidak didukung untuk salah satu dokumen.',
            'dokumen.*.max' => 'Ukuran file terlalu besar (maks 5MB) untuk salah satu dokumen.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                ->withErrors($validator)
                ->withInput();
        }

        $dokumenUploadedCount = 0;
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $key => $uploadedFile) {
                if ($uploadedFile->isValid() && array_key_exists($key, $predefinedDokumenTypesOnStore)) {
                    $namaDokumenStandar = $predefinedDokumenTypesOnStore[$key];

                    $existingDoc = DokumenPendaftar::where('pendaftar_id', $pendaftar->id)
                        ->where('nama_dokumen', $namaDokumenStandar)
                        ->first();
                    if ($existingDoc) {
                        if ($existingDoc->file_path && Storage::disk('public')->exists($existingDoc->file_path)) {
                            Storage::disk('public')->delete($existingDoc->file_path);
                        }
                        $existingDoc->delete();
                    }

                    $filePath = $uploadedFile->store("dokumen_pendaftar/{$pendaftar->id}", 'public');
                    DokumenPendaftar::create([
                        'pendaftar_id' => $pendaftar->id,
                        'nama_dokumen' => $namaDokumenStandar,
                        'file_path' => $filePath,
                        'tipe_file' => $uploadedFile->getClientOriginalExtension(),
                        'status_validasi' => 'Belum Diverifikasi',
                    ]);
                    $dokumenUploadedCount++;
                }
            }
        }

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        if ($dokumenUploadedCount > 0) {
            return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                ->with('success', $dokumenUploadedCount.' dokumen berhasil diunggah/diperbarui.');
        } else {
            return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                ->with('info', 'Tidak ada dokumen baru yang diunggah atau kunci dokumen tidak sesuai.');
        }
    }

    public function updateStatusDokumen(Request $request, Pendaftar $pendaftar, DokumenPendaftar $dokumenPendaftar)
    {
        if ($dokumenPendaftar->pendaftar_id !== $pendaftar->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validator = Validator::make($request->all(), [
            'status_validasi' => ['required', Rule::in(['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Perlu Revisi'])],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                ->withErrors($validator, 'updateStatusDokumenError_'.$dokumenPendaftar->id)
                ->withInput();
        }

        $dokumenPendaftar->status_validasi = $request->status_validasi;
        $dokumenPendaftar->save();

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
            ->with('success', 'Status validasi dokumen "'.$dokumenPendaftar->nama_dokumen.'" berhasil diperbarui.');
    }

    public function destroyDokumen(Pendaftar $pendaftar, DokumenPendaftar $dokumenPendaftar)
    {
        if ($dokumenPendaftar->pendaftar_id !== $pendaftar->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($dokumenPendaftar->file_path && Storage::disk('public')->exists($dokumenPendaftar->file_path)) {
            Storage::disk('public')->delete($dokumenPendaftar->file_path);
        }
        $dokumenPendaftar->delete();

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    protected function cekDanUbahStatusLamaranPendaftar(Pendaftar $pendaftar)
    {
        $semuaDokumenWajibValid = true;
        $pesanDetail = [];
        $userNama = $pendaftar->user->name ?? $pendaftar->user->username;

        foreach ($this->dokumenWajibNames as $namaDocWajib) {
            $doc = $pendaftar->dokumenPendaftars()->where('nama_dokumen', $namaDocWajib)->first();
            if (! $doc || $doc->status_validasi !== 'Valid') {
                $semuaDokumenWajibValid = false;
                $pesanDetail[] = $namaDocWajib.($doc ? ' (Status: '.$doc->status_validasi.')' : ' (Belum diunggah)');
            }
        }

        $currentStatus = $pendaftar->status_lamaran;

        if ($semuaDokumenWajibValid) {
            if ($currentStatus === 'Pending') {
                $pendaftar->status_lamaran = 'Ditinjau';
                $pendaftar->save();
                session()->flash('info', 'Semua dokumen wajib '.$userNama.' telah valid. Status lamaran diubah menjadi "Ditinjau".');
            }
        } else {
            if (! in_array($currentStatus, ['Pending', 'Ditolak'])) {
                $oldStatusForMsg = $pendaftar->status_lamaran;
                $pendaftar->status_lamaran = 'Pending';
                $pendaftar->save();
                session()->flash('warning', 'Dokumen pendaftar '.$userNama.' belum lengkap/valid ('.implode(', ', $pesanDetail).'). Status lamaran ('.$oldStatusForMsg.') dikembalikan ke "Pending".');
            }
        }
    }
}
