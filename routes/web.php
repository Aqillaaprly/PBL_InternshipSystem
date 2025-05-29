<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AdminDashboardController; // Controller baru untuk dashboard admin
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\LowonganController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController; // Untuk data mahasiswa oleh admin
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController; // Untuk data pembimbing oleh admin
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController; // Untuk laporan oleh admin
use App\Http\Controllers\Admin\ProfileController as AdminProfileController; // Untuk profil admin


// Mengarahkan halaman utama ('/') ke halaman login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');

// LOGIN dan LOGOUT Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('log-in'); 
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ADMIN GROUP
Route::middleware(['auth', 'authorize:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
     Route::get('/perusahaan', [CompanyController::class, 'index'])->name('perusahaan.index'); // Hanya ini untuk CompanyController

    // Manajemen User Data (jika berbeda dari dashboard utama)
    Route::get('/userdata', [UserController::class, 'view'])->name('userdata.index');
    Route::get('/userdata/create', [UserController::class, 'create'])->name('userdata.create');
    Route::post('/userdata', [UserController::class, 'store'])->name('userdata.store');
    Route::get('/userdata/{user}/edit', [UserController::class, 'edit'])->name('userdata.edit'); // Menggunakan {user} untuk route model binding
    Route::put('/userdata/{user}', [UserController::class, 'update'])->name('userdata.update');
    Route::delete('/userdata/{user}', [UserController::class, 'destroy'])->name('userdata.destroy');

    // Manajemen Perusahaan
    Route::get('/perusahaan', [CompanyController::class, 'index'])->name('perusahaan.index');

    // Manajemen Lowongan
    Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');
   

    // Manajemen Pendaftar
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');


    // Routes untuk link navbar admin yang belum ada
    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::get('/data-pembimbing', [AdminPembimbingController::class, 'index'])->name('data_pembimbing');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    // Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan'); // Jika ada halaman pengaturan

});

// DOSEN GROUP
Route::middleware(['auth', 'authorize:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dosen.dashboard'); // Pastikan view ini ada
    })->name('dashboard');
});

// MAHASISWA GROUP
Route::middleware(['auth', 'authorize:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('mahasiswa.dashboard'); // Pastikan view ini ada
    })->name('dashboard');
});

// PERUSAHAAN GROUP
Route::middleware(['auth', 'authorize:perusahaan'])->prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('perusahaan.dashboard'); // Pastikan view ini ada
    })->name('dashboard');
    // Tambahkan route lain untuk perusahaan di sini
});