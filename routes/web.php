<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Mengarahkan halaman utama ('/') ke halaman login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');

// LOGIN dan LOGOUT Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('log-in');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ADMIN GROUP
Route::middleware(['auth', 'authorize:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen User Data (oleh Admin)
    Route::get('/users', [UserController::class, 'view'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Manajemen Perusahaan oleh Admin
    // Jika AdminCompanyController adalah resource controller standar, Anda bisa menyederhanakannya:
    Route::resource('perusahaan', AdminCompanyController::class);
    // Jika Anda tetap memerlukan route 'destroy' terpisah karena alasan tertentu,
    // pastikan 'except' di resource controller sudah benar atau hapus 'except' jika 'destroy' ditangani oleh resource.
    // Untuk saat ini, saya asumsikan resource controller sudah menangani destroy.

    // Manajemen Lowongan oleh Admin
    Route::resource('lowongan', AdminLowonganController::class);

    // Manajemen Pendaftar oleh Admin
    Route::resource('pendaftar', AdminPendaftarController::class);

    // Data Mahasiswa (dikelola Admin)
    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::get('/data-mahasiswa/create', [AdminMahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/data-mahasiswa', [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
    // Pastikan {mahasiswa} di sini adalah instance dari User, bukan Mahasiswa model,
    // jika AdminMahasiswaController menggunakan route model binding untuk User.
    Route::get('/data-mahasiswa/{mahasiswa}/show', [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/data-mahasiswa/{mahasiswa}/edit', [AdminMahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Data Pembimbing (dikelola Admin)
    Route::get('/data-pembimbing', [AdminPembimbingController::class, 'index'])->name('data_pembimbing');
    // CRUD untuk pembimbing oleh admin bisa ditambahkan di sini jika diperlukan

    // Laporan (dikelola Admin)
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');

    // Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Rute yang duplikat telah dihapus. Definisi di atas sudah mencakupnya.
});

// DOSEN GROUP
Route::middleware(['auth', 'authorize:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dosen.dashboard'); // Pastikan view ini ada: resources/views/dosen/dashboard.blade.php
    })->name('dashboard');
    // Tambahkan route lain untuk dosen di sini
});

// MAHASISWA GROUP
Route::middleware(['auth', 'authorize:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        // Path view diperbaiki dari 'admin.mahasiswa.dashboard' menjadi 'mahasiswa.dashboard'
        return view('mahasiswa.dashboard'); // Pastikan view ini ada: resources/views/mahasiswa/dashboard.blade.php
    })->name('dashboard');

    // Route untuk halaman yang ada di folder mahasiswa
    Route::get('/absensi', function () { return view('mahasiswa.absensi'); })->name('absensi');
    Route::get('/job', function () { return view('mahasiswa.job'); })->name('job');
    Route::get('/profile', function () { return view('mahasiswa.mahasiswaProfile'); })->name('profile');
    Route::get('/perusahaan', function () { return view('mahasiswa.perusahaan'); })->name('perusahaan');
    // Tambahkan route lain untuk mahasiswa di sini
});

// PERUSAHAAN GROUP
Route::middleware(['auth', 'authorize:perusahaan'])->prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('company.dashboard'); // Pastikan view ini ada: resources/views/company/dashboard.blade.php
    })->name('dashboard');

    Route::get('/lowongan', function () { return view('company.lowongan'); })->name('lowongan');
    Route::get('/pendaftar', function () { return view('company.pendaftar'); })->name('pendaftar');
    Route::get('/tambah-lowongan', function () { return view('company.tambah_lowongan'); })->name('tambah_lowongan');
    // Proses tambah lowongan dan pendaftar sebaiknya menggunakan Controller
    // Contoh:
    // Route::post('/lowongan', [App\Http\Controllers\Company\LowonganController::class, 'store'])->name('lowongan.store');
    // Route::post('/pendaftar/update-status', [App\Http\Controllers\Company\PendaftarController::class, 'updateStatus'])->name('pendaftar.update_status');
});

