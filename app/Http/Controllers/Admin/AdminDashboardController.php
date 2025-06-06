<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::count();
        $jumlahPendaftar = Pendaftar::count();
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
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

        return view('admin.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'jumlahMahasiswa',
            'companies',
            'statsProdiDiterima'
        ));
    }
}
