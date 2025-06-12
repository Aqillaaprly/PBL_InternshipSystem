<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\AktivitasMagang; // Import model AktivitasMagang

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Menghitung jumlah perusahaan, lowongan, dan pendaftar
        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::count();
        $jumlahPendaftar = Pendaftar::count();

        // Mengambil role mahasiswa
        $mahasiswaRole = \App\Models\Role::where('name', 'mahasiswa')->first();
        // Menghitung jumlah mahasiswa berdasarkan role
        $jumlahMahasiswa = $mahasiswaRole ? User::where('role_id', $mahasiswaRole->id)->count() : 0;

        // Mengambil 3 perusahaan terbaru yang memiliki lowongan, beserta lowongannya
        $companies = Company::whereHas('lowongans')
            ->with('lowongans')
            ->latest()
            ->take(3)
            ->get();

        // Mengambil pendaftar yang statusnya 'Diterima', beserta detail mahasiswa dan rolenya
        $acceptedPendaftars = Pendaftar::where('status_lamaran', 'Diterima')
            ->with(['user.detailMahasiswa', 'user.role'])
            ->get();

        // Menginisialisasi statistik program studi untuk mahasiswa yang diterima
        $statsProdiDiterima = [
            'Teknik Informatika' => 0,
            'Sistem Informasi Bisnis' => 0,
            'Lainnya' => 0, // Untuk program studi yang tidak terdaftar eksplisit
        ];

        // Menghitung statistik program studi
        if ($mahasiswaRole) {
            foreach ($acceptedPendaftars as $pendaftar) {
                // Memastikan user, role, dan detailMahasiswa ada dan role adalah mahasiswa
                if ($pendaftar->user &&
                    $pendaftar->user->role_id == $mahasiswaRole->id &&
                    $pendaftar->user->detailMahasiswa &&
                    ! empty($pendaftar->user->detailMahasiswa->program_studi)
                ) {
                    $prodi = $pendaftar->user->detailMahasiswa->program_studi;
                    // Menambahkan hitungan berdasarkan program studi yang terdaftar, jika tidak, masukkan ke 'Lainnya'
                    if (array_key_exists($prodi, $statsProdiDiterima)) {
                        $statsProdiDiterima[$prodi]++;
                    } else {
                        $statsProdiDiterima['Lainnya']++;
                    }
                } elseif ($pendaftar->user && $pendaftar->user->role_id == $mahasiswaRole->id) {
                    // Jika mahasiswa tapi program studi tidak terdefinisi
                    $statsProdiDiterima['Lainnya']++;
                }
            }
        }

        // Mengambil 5 aktivitas magang terbaru beserta relasi mahasiswa dan perusahaan PIC
        // Mahasiswa dihubungkan melalui user_id, dan perusahaan PIC dihubungkan melalui user_id
        // Jadi, kita perlu menelusuri dari AktivitasMagang -> Mahasiswa -> User, dan AktivitasMagang -> User (perusahaanPic)
        $aktivitas = AktivitasMagang::with([
            'mahasiswa.user', // Mengakses nama mahasiswa melalui relasi user pada model Mahasiswa
            'perusahaanPic' // Mengakses nama perusahaan PIC melalui relasi perusahaanPic pada model User
        ])
        ->latest() // Urutkan dari yang terbaru
        ->take(5) // Ambil 5 data terbaru
        ->get();

        // Mengirimkan semua data ke view dashboard
        return view('admin.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'jumlahMahasiswa',
            'companies',
            'statsProdiDiterima',
            'aktivitas' // Tambahkan data aktivitas ke view
        ));
    }
}

