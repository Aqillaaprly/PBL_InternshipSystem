<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\DokumenPendaftar;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PendaftarController extends Controller
{
    private $dokumenWajibNames = [
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

    /**
     * Menampilkan daftar pendaftar untuk lowongan perusahaan yang sedang login.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan. Silahkan hubungi admin.');
        }

        $query = Pendaftar::with(['user', 'lowongan.company', 'dokumenPendaftars'])
            ->whereHas('lowongan', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            });

        // --- PERUBAHAN DI SINI: Default filter untuk mengecualikan 'Pending' ---
        // Jika filter status_lamaran TIDAK diisi, tambahkan kondisi untuk tidak menampilkan 'Pending'.
        if (! $request->filled('status_lamaran')) {
            $query->where('status_lamaran', '!=', 'Pending');
        } else {
            // Jika filter status_lamaran DIISI, terapkan filter sesuai permintaan user.
            $query->where('status_lamaran', $request->status_lamaran);
        }
        // --- AKHIR PERUBAHAN ---

        // Terapkan filter pencarian (nama user/username atau judul lowongan)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($uq) use ($searchTerm) {
                    $uq->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('lowongan', function ($lq) use ($searchTerm) {
                        $lq->where('judul', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Terapkan filter lowongan_id jika ada
        if ($request->filled('lowongan_id')) {
            $query->where('lowongan_id', $request->lowongan_id);
        }

        // Terapkan filter status dokumen jika ada
        if ($request->filled('document_status_filter')) {
            $filterStatus = $request->document_status_filter;
            if ($filterStatus === 'Empty') {
                $query->doesntHave('dokumenPendaftars');
            } else {
                $query->whereHas('dokumenPendaftars', function ($q) use ($filterStatus) {
                    $q->where('status_validasi', $filterStatus);
                });
            }
        }

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

        $lowonganPerusahaan = $company->lowongans()->orderBy('judul')->get();

        $selectedStatusLamaran = $request->input('status_lamaran');
        $selectedLowonganId = $request->input('lowongan_id');
        $selectedDocumentStatusFilter = $request->input('document_status_filter');

        return view('perusahaan.pendaftar', compact(
            'pendaftars',
            'lowonganPerusahaan',
            'selectedStatusLamaran',
            'selectedLowonganId',
            'selectedDocumentStatusFilter'
        ));
    }

    /**
     * Menampilkan detail pendaftar tertentu.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show(Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company || $pendaftar->lowongan->company_id !== $company->id) {
            abort(403, 'Aksi tidak diizinkan. Pendaftar ini bukan untuk lowongan perusahaan Anda.');
        }

        $pendaftar->load(['user', 'user.detailMahasiswa', 'lowongan.company', 'dokumenPendaftars']);

        return view('perusahaan.pendaftar.detail', compact('pendaftar'));
    }

    /**
     * Memperbarui status lamaran pendaftar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatusLamaran(Request $request, Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company || $pendaftar->lowongan->company_id !== $company->id) {
            abort(403, 'Aksi tidak diizinkan. Pendaftar ini bukan untuk lowongan perusahaan Anda.');
        }

        $validator = Validator::make($request->all(), [
            'status_lamaran' => 'required|in:Ditinjau,Diterima,Ditolak',
        ]);

        if ($validator->fails()) {
            return redirect()->route('perusahaan.pendaftar.show', $pendaftar->id)
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
                $pesanError = 'Tidak dapat mengubah status lamaran ke "'.$newStatusLamaran.'". Dokumen wajib berikut belum valid atau belum diunggah: '.implode(', ', $dokumenBelumValidInfo).'. Harap validasi dokumen terlebih dahulu atau set status ke "Pending" atau "Ditolak".';

                return redirect()->route('perusahaan.pendaftar.show', $pendaftar->id)
                    ->with('error', $pesanError)
                    ->withInput();
            }
        }

        $pendaftar->status_lamaran = $newStatusLamaran;
        $pendaftar->save();

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        return redirect()->route('perusahaan.pendaftar.index')->with('success', 'Status lamaran berhasil diperbarui.');
    }

    /**
     * Menampilkan dokumen untuk pendaftar tertentu dan memungkinkan validasi.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function showDokumen(Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company || $pendaftar->lowongan->company_id !== $company->id) {
            abort(403, 'Aksi tidak diizinkan. Dokumen pendaftar ini bukan untuk lowongan perusahaan Anda.');
        }

        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']);

        $predefinedDokumenTypesForView = [];
        $storageDokumenTypes = $this->getPredefinedDokumenTypesForStorage();

        foreach ($storageDokumenTypes as $key => $namaDokumenStandar) {
            $label = $namaDokumenStandar;
            $opsionalKeywords = ['sertifikat kompetensi', 'surat balasan', 'bpjs atau asuransi lain', 'sktm atau kip kuliah', 'proposal magang'];
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

        return view('perusahaan.pendaftar.show_dokumen', compact('pendaftar', 'predefinedDokumenTypesForView'));
    }

    /**
     * Memperbarui status validasi dokumen tertentu untuk pendaftar.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function updateStatusDokumen(Request $request, Pendaftar $pendaftar, DokumenPendaftar $dokumenPendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company || $pendaftar->lowongan->company_id !== $company->id || $dokumenPendaftar->pendaftar_id !== $pendaftar->id) {
            abort(403, 'Aksi tidak diizinkan. Dokumen ini tidak terkait atau bukan untuk lowongan perusahaan Anda.');
        }

        $validator = Validator::make($request->all(), [
            'status_validasi' => ['required', Rule::in(['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Perlu Revisi'])],
        ]);

        if ($validator->fails()) {
            return redirect()->route('perusahaan.pendaftar.showDokumen', $pendaftar->id)
                ->withErrors($validator, 'updateStatusDokumenError_'.$dokumenPendaftar->id)
                ->withInput();
        }

        $dokumenPendaftar->status_validasi = $request->status_validasi;
        $dokumenPendaftar->save();

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());

        return redirect()->route('perusahaan.pendaftar.showDokumen', $pendaftar->id)
            ->with('success', 'Status validasi dokumen "'.$dokumenPendaftar->nama_dokumen.'" berhasil diperbarui.');
    }

    /**
     * Helper method to check and update Pendaftar's status_lamaran based on document validation.
     *
     * @return void
     */
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
            if ($currentStatus === 'Ditinjau') {
                $pendaftar->status_lamaran = 'Ditinjau';
                $pendaftar->save();
                session()->flash('info', 'Semua dokumen wajib '.$userNama.' telah valid. Status lamaran diubah menjadi "Ditinjau".');
            }
        } else {
            if (! in_array($currentStatus, ['Ditinjau', 'Ditolak'])) {
                $oldStatusForMsg = $pendaftar->status_lamaran;
                $pendaftar->status_lamaran = 'Ditinjau';
                $pendaftar->save();
                session()->flash('warning', 'Dokumen pendaftar '.$userNama.' belum lengkap/valid ('.implode(', ', $pesanDetail).'). Status lamaran ('.$oldStatusForMsg.') dikembalikan ke "Pending".');
            }
        }
    }
}