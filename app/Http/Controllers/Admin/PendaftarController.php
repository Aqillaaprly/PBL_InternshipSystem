<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Lowongan;
use App\Models\User;
use App\Models\DokumenPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PendaftarController extends Controller
{
    // Daftar nama dokumen yang dianggap wajib untuk bisa lanjut dari status 'Pending'
    // Pastikan nama-nama ini SAMA PERSIS dengan yang ada di getPredefinedDokumenTypesForStorage()
    // dan yang disimpan di kolom 'nama_dokumen' pada tabel dokumen_pendaftars.
    private $dokumenWajibNames = [
        'Daftar Riwayat Hidup',
        'KHS atau Transkrip Nilai',
        'KTP',
        'KTM',
        'Surat Izin Orang Tua',
        'Pakta Integritas',
        // 'Surat Balasan Industri', // Tambahkan jika ini juga wajib divalidasi agar status lamaran bisa maju
    ];

    /**
     * Helper untuk mendapatkan daftar nama dokumen standar untuk penyimpanan.
     * Kuncinya adalah 'file_input_name' di form, nilainya adalah 'Nama Dokumen Standar di DB'.
     */
    private function getPredefinedDokumenTypesForStorage(): array
    {
        return [
            'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
            'surat_balasan' => 'Surat Balasan Industri',
            'pakta_integritas' => 'Pakta Integritas',
            'cv' => 'Daftar Riwayat Hidup',
            'khs_transkrip' => 'KHS atau Transkrip Nilai',
            'ktp' => 'KTP',
            'ktm' => 'KTM',
            'surat_izin_ortu' => 'Surat Izin Orang Tua',
            'bpjs_asuransi' => 'Kartu BPJS atau Asuransi Lain',
            'sktm_kip' => 'SKTM atau KIP Kuliah',
            'proposal_magang' => 'Proposal Magang',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pendaftar::with(['user', 'lowongan.company', 'dokumenPendaftars']); // Eager load dokumenPendaftars

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

        $pendaftars = $query->latest('tanggal_daftar')->paginate(10)->withQueryString();
        
        // Mengirim daftar dokumen wajib ke view agar konsisten
        $dokumenWajibGlobal = $this->dokumenWajibNames;

        return view('admin.Company.pendaftar', compact('pendaftars', 'dokumenWajibGlobal'));
    }

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:pendaftars,user_id,NULL,id,lowongan_id,' . $request->lowongan_id,
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
        
        // Panggil pengecekan setelah pendaftar dibuat, untuk memastikan status lamaran awal benar
        // (misalnya, jika status default seharusnya 'Pending' karena belum ada dokumen)
        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());


        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']);
        return view('admin.pendaftar.show', compact('pendaftar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pendaftar $pendaftar)
    {
        $pendaftar->load('user', 'lowongan.company');
        return view('admin.pendaftar.edit', compact('pendaftar'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        // Logika pengecekan dokumen sebelum mengubah status ke tahap lanjut
        if (!in_array($newStatusLamaran, ['Pending', 'Ditolak'])) {
            $semuaDokumenWajibValid = true;
            $dokumenBelumValidInfo = [];

            foreach ($this->dokumenWajibNames as $namaDocWajib) {
                $doc = $pendaftar->dokumenPendaftars()->where('nama_dokumen', $namaDocWajib)->first();
                if (!$doc || $doc->status_validasi !== 'Valid') {
                    $semuaDokumenWajibValid = false;
                    $dokumenBelumValidInfo[] = $namaDocWajib . ($doc ? ' (Status: ' . $doc->status_validasi . ')' : ' (Belum diunggah)');
                }
            }

            if (!$semuaDokumenWajibValid) {
                $pesanError = 'Tidak dapat mengubah status lamaran ke "' . $newStatusLamaran . '". Dokumen berikut belum valid atau belum diunggah: ' . implode(', ', $dokumenBelumValidInfo) . '. Harap validasi dokumen terlebih dahulu atau set status ke "Pending".';
                return redirect()->route('admin.pendaftar.edit', $pendaftar->id) // Bisa juga redirect ke showDokumen
                             ->with('error', $pesanError)
                             ->withInput();
            }
        }

        $pendaftar->update($request->only(['tanggal_daftar', 'status_lamaran', 'catatan_admin']));
        
        // Jika status lamaran secara manual diubah (misalnya dari Ditinjau ke Pending),
        // panggil cekDanUbahStatusLamaranPendaftar untuk memastikan konsistensi.
        // Atau jika statusnya tetap Pending dan dokumen sudah diupdate.
        if ($oldStatusLamaran !== $newStatusLamaran || $newStatusLamaran === 'Pending') {
            $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh());
        }

        return redirect()->route('admin.pendaftar.index')->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pendaftar $pendaftar)
    {
        // Hapus file fisik dokumen pendaftar dari storage
        foreach ($pendaftar->dokumenPendaftars as $dokumen) {
            if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
        }
        // Record DokumenPendaftar akan terhapus otomatis jika onDelete('cascade') di-set pada foreign key di migrasi.
        // Jika tidak, Anda perlu menghapusnya secara manual: $pendaftar->dokumenPendaftars()->delete();
        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }

    // === METODE UNTUK MANAJEMEN DOKUMEN ===

    public function showDokumen(Pendaftar $pendaftar)
    {
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']);
        
        $predefinedDokumenTypesForView = [];
        $storageDokumenTypes = $this->getPredefinedDokumenTypesForStorage();

        foreach ($storageDokumenTypes as $key => $namaDokumenStandar) {
            $label = $namaDokumenStandar;
            $opsionalKeywords = ['sertifikat kompetensi', 'surat balasan', 'bpjs atau asuransi lain', 'sktm atau kip kuliah', 'proposal magang'];
            $isOptional = false;
            foreach ($opsionalKeywords as $keyword) {
                // Memastikan kata kunci ditemukan sebagai kata utuh atau bagian dari nama dokumen
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
            $rules["dokumen[{$key}]"] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120'; // Max 5MB
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
                        $existingDoc->delete(); // Hapus record lama untuk digantikan
                    }

                    $filePath = $uploadedFile->store("dokumen_pendaftar/{$pendaftar->id}", 'public');
                    DokumenPendaftar::create([
                        'pendaftar_id' => $pendaftar->id,
                        'nama_dokumen' => $namaDokumenStandar,
                        'file_path' => $filePath,
                        'tipe_file' => $uploadedFile->getClientOriginalExtension(),
                        'status_validasi' => 'Belum Diverifikasi', // Default status saat unggah baru
                    ]);
                    $dokumenUploadedCount++;
                }
            }
        }

        // Panggil pengecekan status lamaran setelah dokumen diunggah/diperbarui
        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh()); // Gunakan fresh() untuk data terbaru

        if ($dokumenUploadedCount > 0) {
            return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                             ->with('success', $dokumenUploadedCount . ' dokumen berhasil diunggah/diperbarui.');
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
                        ->withErrors($validator, 'updateStatusDokumenError_' . $dokumenPendaftar->id) // Error bag unik
                        ->withInput();
        }

        $dokumenPendaftar->status_validasi = $request->status_validasi;
        $dokumenPendaftar->save();

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh()); // Gunakan fresh()

        return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                         ->with('success', 'Status validasi dokumen "' . $dokumenPendaftar->nama_dokumen . '" berhasil diperbarui.');
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

        $this->cekDanUbahStatusLamaranPendaftar($pendaftar->fresh()); // Gunakan fresh()

        return redirect()->route('admin.pendaftar.showDokumen', $pendaftar->id)
                         ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Method helper untuk memeriksa dan mengubah status lamaran pendaftar
     * berdasarkan validitas dokumen wajib.
     */
    protected function cekDanUbahStatusLamaranPendaftar(Pendaftar $pendaftar)
    {
        $semuaDokumenWajibValid = true;
        $pesanDetail = []; 
        $userNama = $pendaftar->user->name ?? $pendaftar->user->username;

        foreach ($this->dokumenWajibNames as $namaDocWajib) {
            $doc = $pendaftar->dokumenPendaftars()->where('nama_dokumen', $namaDocWajib)->first();
            if (!$doc || $doc->status_validasi !== 'Valid') {
                $semuaDokumenWajibValid = false;
                $pesanDetail[] = $namaDocWajib . ($doc ? ' (Status: ' . $doc->status_validasi . ')' : ' (Belum diunggah)');
                // Tidak perlu break jika ingin mengumpulkan semua dokumen yang bermasalah untuk pesan
            }
        }

        $currentStatus = $pendaftar->status_lamaran;

        if ($semuaDokumenWajibValid) {
            if ($currentStatus === 'Pending') {
                $pendaftar->status_lamaran = 'Ditinjau';
                $pendaftar->save();
                session()->flash('info', 'Semua dokumen wajib ' . $userNama . ' telah valid. Status lamaran diubah menjadi "Ditinjau".');
            }
        } else {
            if (!in_array($currentStatus, ['Pending', 'Ditolak'])) {
                $pendaftar->status_lamaran = 'Pending';
                $pendaftar->save();
                session()->flash('warning', 'Dokumen pendaftar ' . $userNama . ' belum lengkap/valid (' . implode(', ', $pesanDetail) . '). Status lamaran dikembalikan ke "Pending".');
            }
        }
    }
}