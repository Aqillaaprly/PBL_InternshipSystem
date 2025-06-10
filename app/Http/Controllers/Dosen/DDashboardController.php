<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagang;
use App\Models\BimbinganMagang;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Role; // Tambahkan ini jika belum ada

class DDashboardController extends Controller
{
    /**
     * Display the dosen dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::count();
        $jumlahPendaftar = Pendaftar::count();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first(); // Pastikan Role diimpor atau gunakan \App\Models\Role
        $jumlahMahasiswa = $mahasiswaRole ? User::where('role_id', $mahasiswaRole->id)->count() : 0;

        $companies = Company::whereHas('lowongans')
            ->with('lowongans')
            ->latest()
            ->take(3)
            ->get();

        $acceptedPendaftars = Pendaftar::where('status_lamaran', 'Diterima')
            ->with(['user.detailMahasiswa', 'user.role'])
            ->get();

        $statsProdiDiterima = [
            'Teknik Informatika' => 0,
            'Sistem Informasi Bisnis' => 0,
            'Lainnya' => 0,
        ];

        if ($mahasiswaRole) {
            foreach ($acceptedPendaftars as $pendaftar) {
                if ($pendaftar->user &&
                    $pendaftar->user->role_id == $mahasiswaRole->id &&
                    $pendaftar->user->detailMahasiswa &&
                    ! empty($pendaftar->user->detailMahasiswa->program_studi)
                ) {
                    $prodi = $pendaftar->user->detailMahasiswa->program_studi;
                    if (array_key_exists($prodi, $statsProdiDiterima)) {
                        $statsProdiDiterima[$prodi]++;
                    } else {
                        $statsProdiDiterima['Lainnya']++;
                    }
                } elseif ($pendaftar->user && $pendaftar->user->role_id == $mahasiswaRole->id) {
                    $statsProdiDiterima['Lainnya']++;
                }
            }
        }
        
        // === Data Bimbingan ===
        $bimbingans = BimbinganMagang::with([
                'mahasiswa.detailMahasiswa',
                'mahasiswa',
            ])
            ->latest()
            ->paginate(2); // Menggunakan paginate untuk tabel bimbingan jika diinginkan

        // === Data Absensi Mahasiswa (rekap total hadir per mahasiswa bimbingan) ===
        $data = BimbinganMagang::with([
                'mahasiswa',
                'pembimbing',
                'company'
            ])->paginate(5);
            $data = $data->map(function ($item) {
            $total_approved = AktivitasMagang::where('mahasiswa_id', $item->mahasiswa_id ?? $item->mahasiswa->id)
                                ->where('status_verifikasi', 'approved')
                                ->count();

            $item->total_hadir = $total_approved; // kita anggap approved = hadir
            return $item;
        });

        return view('dosen.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'jumlahMahasiswa',
            'companies',
            'statsProdiDiterima',
            'bimbingans', // Pastikan ini juga ada di compact
            'data' // <--- INI PERBAIKANNYA: Tambahkan variabel $data di sini
        ));
    }
}