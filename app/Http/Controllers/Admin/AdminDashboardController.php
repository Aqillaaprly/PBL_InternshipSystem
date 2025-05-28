<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\User; // Untuk menghitung mahasiswa atau dosen jika perlu

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil data statistik yang dibutuhkan untuk dashboard
        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::count();
        // Asumsi Pendaftar adalah model untuk pendaftar, sesuaikan jika berbeda
        $jumlahPendaftar = Pendaftar::count(); 
        
        // Contoh menghitung jumlah mahasiswa (user dengan role_id tertentu)
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
        $jumlahMahasiswa = $mahasiswaRole ? User::where('role_id', $mahasiswaRole->id)->count() : 0;
         $companies = Company::latest()->get();

        return view('admin.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'jumlahMahasiswa',
            'companies' // Ensure this string matches the variable name EXACTLY: 'companies' (lowercase 'c')
        ));
    }
}
