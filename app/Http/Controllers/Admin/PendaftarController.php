<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Lowongan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Untuk logging jika ada masalah

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request untuk potensi filter/search
    {
        // Query dasar untuk pendaftar beserta relasi yang dibutuhkan
        $query = Pendaftar::with(['user', 'lowongan.company']);

        // Contoh sederhana untuk pencarian berdasarkan nama mahasiswa atau judul lowongan
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

        $pendaftars = $query->latest('tanggal_daftar')->paginate(10)->withQueryString(); // withQueryString() untuk menjaga parameter search/filter saat paginasi
        $jumlahPendaftar = Pendaftar::count(); // Ini bisa juga $pendaftars->total() jika tidak ada filter yang kompleks

        // Debugging jika diperlukan (hapus atau komentari di produksi)
        // Log::info('Data Pendaftar:', $pendaftars->toArray());
        // if ($pendaftars->isEmpty()) {
        //     Log::info('Tidak ada pendaftar ditemukan dengan kriteria saat ini.');
        // }

        // Pastikan path view ini benar: resources/views/admin/Company/pendaftar.blade.php
        return view('admin.Company.pendaftar', compact('pendaftars', 'jumlahPendaftar'));
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
                            ->with('company') // Eager load company
                            ->orderBy('judul')->get();

        // Jika view create ada di admin/Company/pendaftar/create.blade.php
        // return view('admin.Company.pendaftar.create', compact('mahasiswas', 'lowongans'));
        // Jika view create ada di admin/pendaftar/create.blade.php
        return view('admin.pendaftar.create', compact('mahasiswas', 'lowongans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'lowongan_id' => 'required|exists:lowongans,id',
            'tanggal_daftar' => 'required|date',
            'status_lamaran' => 'required|in:Pending,Ditinjau,Wawancara,Diterima,Ditolak',
            // Tambahkan validasi untuk file jika ada
            // 'surat_lamaran_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // 'cv_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // 'portofolio_path' => 'nullable|file|mimes:pdf,zip,rar|max:5120',
            'catatan_admin' => 'nullable|string',
        ]);

        $createViewPath = 'admin.pendaftar.create'; // Sesuaikan jika path view create berbeda

        if ($validator->fails()) {
            return redirect()->route($createViewPath)
                        ->withErrors($validator)
                        ->withInput();
        }

        $existingPendaftar = Pendaftar::where('user_id', $request->user_id)
                                     ->where('lowongan_id', $request->lowongan_id)
                                     ->first();
        if ($existingPendaftar) {
            return redirect()->route($createViewPath)
                        ->with('error', 'Mahasiswa ini sudah terdaftar pada lowongan yang sama.')
                        ->withInput();
        }

        $dataToStore = $request->only(['user_id', 'lowongan_id', 'tanggal_daftar', 'status_lamaran', 'catatan_admin']);

        // Contoh penanganan upload file (sesuaikan dengan kebutuhan Anda)
        // if ($request->hasFile('cv_path')) {
        //     $dataToStore['cv_path'] = $request->file('cv_path')->store('dokumen_pendaftar/cv', 'public');
        // }
        // if ($request->hasFile('surat_lamaran_path')) {
        //     $dataToStore['surat_lamaran_path'] = $request->file('surat_lamaran_path')->store('dokumen_pendaftar/surat_lamaran', 'public');
        // }
        // ... dan seterusnya untuk file lain

        Pendaftar::create($dataToStore);

        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']); // 'dokumenPendaftars' jika sudah diimplementasikan
        // Jika view show ada di admin/Company/pendaftar/show.blade.php
        // return view('admin.Company.pendaftar.show', compact('pendaftar'));
        return view('admin.pendaftar.show', compact('pendaftar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pendaftar $pendaftar)
    {
        $mahasiswas = User::whereHas('role', function ($query) {
            $query->where('name', 'mahasiswa');
        })->orderBy('name')->get();
        $lowongans = Lowongan::where('status', 'Aktif')->orderBy('judul')->get();

        // Jika view edit ada di admin/Company/pendaftar/edit.blade.php
        // return view('admin.Company.pendaftar.edit', compact('pendaftar', 'mahasiswas', 'lowongans'));
        return view('admin.pendaftar.edit', compact('pendaftar', 'mahasiswas', 'lowongans'));
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
             // Tambahkan validasi untuk file jika bisa diubah
        ]);

        $editViewPath = 'admin.pendaftar.edit'; // Sesuaikan jika path view edit berbeda

        if ($validator->fails()) {
            return redirect()->route($editViewPath, $pendaftar->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $dataToUpdate = $request->only(['tanggal_daftar', 'status_lamaran', 'catatan_admin']);

        // Contoh penanganan update file (hapus lama, simpan baru)
        // if ($request->hasFile('cv_path')) {
        //     if ($pendaftar->cv_path && Storage::disk('public')->exists($pendaftar->cv_path)) {
        //         Storage::disk('public')->delete($pendaftar->cv_path);
        //     }
        //     $dataToUpdate['cv_path'] = $request->file('cv_path')->store('dokumen_pendaftar/cv', 'public');
        // }

        $pendaftar->update($dataToUpdate);
        return redirect()->route('admin.pendaftar.index')->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pendaftar $pendaftar)
    {
        // Tambahan: Hapus file terkait dari storage jika ada sebelum menghapus record
        // if ($pendaftar->cv_path && Storage::disk('public')->exists($pendaftar->cv_path)) {
        //     Storage::disk('public')->delete($pendaftar->cv_path);
        // }
        // ... dan seterusnya untuk file lain

        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }
}