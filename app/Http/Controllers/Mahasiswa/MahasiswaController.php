<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BimbinganMagang;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;

class MahasiswaController extends Controller
{
    // Menampilkan halaman dashboard mahasiswa dengan statistik
    public function dashboard()
    {
        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::count();
        $jumlahPendaftar = Pendaftar::count();

        return view('mahasiswa.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar'
        ));
    }

    // Menampilkan dosen pembimbing aktif bagi mahasiswa
    public function lihatPembimbing()
    {
        $userMahasiswa = Auth::user();

        $bimbinganAktif = BimbinganMagang::with(['pembimbing.user', 'company'])
            ->where('mahasiswa_user_id', $userMahasiswa->id)
            ->where('status_bimbingan', 'Aktif')
            ->first();

        return view('mahasiswa.dosen_pembimbing', compact('bimbinganAktif'));
    }

    // ✅ Menampilkan daftar bimbingan untuk laporan view
    public function laporan()
    {
        $userId = Auth::id();

        $bimbingans = BimbinganMagang::where('mahasiswa_user_id', $userId)
            ->with(['pembimbing.user', 'company', 'lowongan']) // eager load if needed
            ->get();

        return view('mahasiswa.laporan', compact('bimbingans'));
    }
}
