<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\BimbinganMagang;
use App\Models\AktivitasMagang;
use Illuminate\Http\Request;

class AbsensiMahasiswaBimbingan extends Controller
{
    // Tampilkan akumulasi "absensi" berdasarkan AktivitasMagang yang approved
    public function index()
    {
        $data = BimbinganMagang::with('pembimbing', 'company', 'mahasiswa')->get();

        // Hitung total aktivitas "approved" per mahasiswa bimbingan
        $data = $data->map(function ($item) {
            $total_approved = AktivitasMagang::where('mahasiswa_id', $item->mahasiswa_id ?? $item->mahasiswa->id)
                                ->where('status_verifikasi', 'approved')
                                ->count();

            $item->total_hadir = $total_approved; // kita anggap approved = hadir
            return $item;
        });

        return view('dosen.absensimahasiswa', compact('data'));
    }

    // Detail aktivitas magang untuk 1 mahasiswa
    public function show($id)
    {
        $bimbingan = BimbinganMagang::with('pembimbing', 'company', 'mahasiswa')->findOrFail($id);

        // Ambil semua aktivitas magang mahasiswa
        $aktivitasMagang = AktivitasMagang::where('mahasiswa_id', $bimbingan->mahasiswa_id ?? $bimbingan->mahasiswa->id)
                            ->orderBy('tanggal', 'desc')
                            ->get();

        return view('dosen.absensiShow', compact('bimbingan', 'aktivitasMagang'));
    }
}
