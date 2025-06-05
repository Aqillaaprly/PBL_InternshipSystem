<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BimbinganMagang; // Import model BimbinganMagang
use App\Models\Mahasiswa;      // Import model Mahasiswa
use App\Models\Pembimbing;     // Import model Pembimbing
use Illuminate\Support\Str;    // Import Str facade for string manipulation (e.g., Str::limit)

class MahasiswaAktivitasAbsensiController extends Controller
{
    /**
     * Menampilkan daftar aktivitas dan absensi mahasiswa.
     * Admin hanya memiliki hak lihat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Memulai query untuk mengambil data BimbinganMagang
        // Eager load relasi 'mahasiswa.user' dan 'pembimbing' untuk menghindari N+1 query problem
        $query = BimbinganMagang::with(['mahasiswa.user', 'pembimbing']);

        // Logika pencarian (jika ada parameter 'search' dalam request)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Mencari berdasarkan jenis bimbingan
                $q->where('jenis_bimbingan', 'like', "%{$searchTerm}%")
                  // Mencari berdasarkan catatan aktivitas
                  ->orWhere('catatan', 'like', "%{$searchTerm}%")
                  // Mencari berdasarkan nama atau username mahasiswa
                  ->orWhereHas('mahasiswa.user', function ($mq) use ($searchTerm) {
                      $mq->where('name', 'like', "%{$searchTerm}%")
                         ->orWhere('username', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Mengambil data dengan paginasi, diurutkan berdasarkan tanggal terbaru
        // paginate(10) berarti 10 item per halaman
        // withQueryString() memastikan parameter query (seperti 'search') tetap ada saat paginasi
        $aktivitas_absensi = $query->latest('tanggal')->paginate(10)->withQueryString();

        // Mengirim data ke view
        return view('admin.Mahasiswa.aktivitas_absensi', compact('aktivitas_absensi'));
    }

    /**
     * Menampilkan detail aktivitas/absensi tertentu.
     *
     * @param  \App\Models\BimbinganMagang  $aktivitas
     * @return \Illuminate\View\View
     */
    public function show(BimbinganMagang $aktivitas)
    {
        // Eager load relasi yang diperlukan untuk halaman detail
        $aktivitas->load(['mahasiswa.user', 'pembimbing']);

        return view('admin.Mahasiswa.aktivitas_absensi_detail', compact('aktivitas'));
    }
}