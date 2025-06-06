<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BimbinganMagang;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaAktivitasAbsensiController extends Controller
{
    /**
     * Menampilkan daftar aktivitas dan absensi mahasiswa.
     * Admin hanya memiliki hak lihat.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = BimbinganMagang::with(['mahasiswa.user', 'pembimbing']);

        if ($search) {
            $query->whereHas('mahasiswa.user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%');
            })->orWhere('jenis_bimbingan', 'like', '%'.$search.'%')
                ->orWhere('catatan', 'like', '%'.$search.'%');
        }

        $aktivitas_absensi = $query->latest()->paginate(10); // Ambil 10 data per halaman

        return view('admin.Mahasiswa.aktivitas_absensi', compact('aktivitas_absensi'));
    }

    public function show(BimbinganMagang $aktivitas)
    {
        // Eager load relasi yang diperlukan untuk halaman detail
        $aktivitas->load(['mahasiswa.user', 'pembimbing']);

        return view('admin.Mahasiswa.aktivitas_absensi_detail', compact('aktivitas'));
    }
}
