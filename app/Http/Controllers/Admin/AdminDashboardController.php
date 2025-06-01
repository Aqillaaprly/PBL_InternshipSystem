<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User; // Untuk menghitung mahasiswa atau dosen jika perlu
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;

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
        $jumlahPendaftar = Pendaftar::count();

        // Contoh menghitung jumlah mahasiswa (user dengan role_id tertentu)
        // Pastikan model Role dan relasinya dengan User sudah benar
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
        $jumlahMahasiswa = $mahasiswaRole ? User::where('role_id', $mahasiswaRole->id)->count() : 0;

        // Mengambil semua data perusahaan untuk ditampilkan di job.blade.php (jika diperlukan di dashboard)
        // Jika $companies hanya untuk halaman 'admin.job', maka ini bisa dipindahkan ke CompanyController@indexJobView atau semacamnya
        $companies = Company::latest()->paginate(8); // Atau get() jika tidak butuh paginasi di sini

        return view('admin.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'jumlahMahasiswa',
            'companies' // Pastikan variabel ini konsisten dengan yang digunakan di admin.job jika di-include
        ));
    }
}