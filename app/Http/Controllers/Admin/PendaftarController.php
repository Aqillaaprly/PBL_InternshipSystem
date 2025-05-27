<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar; // Anda perlu membuat model Pendaftar
use App\Models\Lowongan;
use App\Models\User; // Asumsi pendaftar adalah User dengan role mahasiswa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PendaftarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Asumsi model Pendaftar memiliki relasi ke User (mahasiswa) dan Lowongan
        $pendaftars = Pendaftar::with(['user', 'lowongan.company'])->latest()->paginate(10);
        // Variabel $jumlahPendaftar untuk dashboard
        $jumlahPendaftar = Pendaftar::count();
        return view('admin.pendaftar', compact('pendaftars', 'jumlahPendaftar')); // resources/views/admin/pendaftar.blade.php
    }

    /**
     * Show the form for creating a new resource.
     * Biasanya pendaftar dibuat oleh mahasiswa, admin mungkin tidak membuat pendaftar secara manual.
     * Jika diperlukan, Anda bisa membuat view dan logic-nya.
     */
    public function create()
    {
        $mahasiswas = User::whereHas('role', function ($query) {
            $query->where('name', 'mahasiswa');
        })->orderBy('name')->get();
        $lowongans = Lowongan::where('status', 'Aktif')->orderBy('judul')->get();
        return view('admin.pendaftar.create', compact('mahasiswas', 'lowongans')); // Buat view ini
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
            'status_lamaran' => 'required|in:Pending,Ditinjau,Diterima,Ditolak,Wawancara',
            'catatan_admin' => 'nullable|string',
        ]);

         if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Cek apakah mahasiswa sudah mendaftar ke lowongan ini
        $existingPendaftar = Pendaftar::where('user_id', $request->user_id)
                                     ->where('lowongan_id', $request->lowongan_id)
                                     ->first();
        if ($existingPendaftar) {
            return redirect()->route('admin.pendaftar.create')
                        ->with('error', 'Mahasiswa ini sudah terdaftar pada lowongan yang sama.')
                        ->withInput();
        }

        Pendaftar::create($request->all());
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Pendaftar $pendaftar) // Menggunakan Route Model Binding
    {
        // Pastikan relasi 'user' dan 'lowongan.company' sudah di-load jika diperlukan
        $pendaftar->load(['user', 'lowongan.company', 'dokumenPendaftars']); // Asumsi ada relasi 'dokumenPendaftars'
        return view('admin.pendaftar.show', compact('pendaftar')); // Buat view ini
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
        return view('admin.pendaftar.edit', compact('pendaftar', 'mahasiswas', 'lowongans')); // Buat view ini
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pendaftar $pendaftar)
    {
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required|exists:users,id', // Biasanya user_id tidak diubah
            // 'lowongan_id' => 'required|exists:lowongans,id', // Biasanya lowongan_id tidak diubah
            'tanggal_daftar' => 'required|date',
            'status_lamaran' => 'required|in:Pending,Ditinjau,Diterima,Ditolak,Wawancara',
            'catatan_admin' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pendaftar.edit', $pendaftar->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $pendaftar->update($request->all());
        return redirect()->route('admin.pendaftar.index')->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pendaftar $pendaftar)
    {
        // Hapus dokumen terkait jika ada
        // foreach ($pendaftar->dokumenPendaftars as $dokumen) {
        //     // Hapus file fisik jika disimpan di storage
        //     // Storage::disk('public')->delete($dokumen->file_path);
        //     $dokumen->delete();
        // }
        $pendaftar->delete();
        return redirect()->route('admin.pendaftar.index')->with('success', 'Data pendaftar berhasil dihapus.');
    }
}