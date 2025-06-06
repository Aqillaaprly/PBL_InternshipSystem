<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\BimbinganMagang;
use App\Models\AbsensiMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiMahasiswaBimbingan extends Controller
{
     // Tampilkan akumulasi absensi semua mahasiswa bimbingan
    public function index()
    {
        $data = BimbinganMagang::with('absensiMagangs')
            ->with('pembimbing', 'company')
            ->with('absensiMagangs')
            ->get();

        // Hitung total hadir per bimbingan
        $data = $data->map(function ($item) {
            $item->total_hadir = $item->absensiMagangs->where('status', 'Hadir')->count();
            return $item;
        });

        return view('dosen.absensimahasiswa', compact('data'));
    }

    // Detail rekap per bulan untuk 1 mahasiswa
    public function show($id)
    {
        $bimbingan = BimbinganMagang::with('absensiMagangs', 'pembimbing', 'company')->findOrFail($id);

        // Rekap per bulan
        $rekap = AbsensiMagang::select(
                DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total_hadir')
            )
            ->where('bimbingan_magang_id', $id)
            ->where('status', 'Hadir')
            ->groupBy(DB::raw('DATE_FORMAT(tanggal, "%Y-%m")'))
            ->orderBy('bulan', 'asc')
            ->get();

        return view('dosen.absensiShow', compact('bimbingan', 'rekap'));
    }
}