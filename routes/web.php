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
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;

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
    Route::resource('perusahaan', AdminCompanyController::class);

    // Manajemen Lowongan oleh Admin
    Route::resource('lowongan', AdminLowonganController::class);

    // Manajemen Pendaftar oleh Admin
    Route::resource('pendaftar', AdminPendaftarController::class);

    // Data Mahasiswa (dikelola Admin)
    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::get('/data-mahasiswa/create', [AdminMahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/data-mahasiswa', [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/data-mahasiswa/{mahasiswa}/show', [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/data-mahasiswa/{mahasiswa}/edit', [AdminMahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Data Pembimbing (dikelola Admin)
    Route::get('/data-pembimbing', [AdminPembimbingController::class, 'index'])->name('data_pembimbing');

    // Laporan (dikelola Admin)
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');

    // Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    Route::middleware(['auth', 'authorize:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... (route admin lainnya) ...

    // Manajemen Penugasan Pembimbing
    Route::resource('penugasan-pembimbing', PenugasanPembimbingController::class);
});
});

// DOSEN GROUP
Route::middleware(['auth', 'authorize:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dosen.dashboard'); // Pastikan view ini ada: resources/views/dosen/dashboard.blade.php
    })->name('dashboard');
Route::get('/mahasiswa-bimbingan', [App\Http\Controllers\Dosen\DosenController::class, 'mahasiswaBimbingan'])->name('mahasiswa.bimbingan');
});



// MAHASISWA GROUP
Route::middleware(['auth', 'authorize:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('mahasiswa.dashboard'); // Path view sudah diperbaiki
    })->name('dashboard');

    // Route untuk halaman yang ada di folder mahasiswa
    Route::get('/absensi', function () { return view('mahasiswa.absensi'); })->name('absensi');
    Route::get('/job', function () { return view('mahasiswa.job'); })->name('job');
    Route::get('/profile', function () { return view('mahasiswa.mahasiswaProfile'); })->name('profile');
    Route::get('/perusahaan', function () { return view('mahasiswa.perusahaan'); })->name('perusahaan');
    Route::get('/dosen-pembimbing', [App\Http\Controllers\Mahasiswa\MahasiswaController::class, 'lihatPembimbing'])->name('lihat.pembimbing');
});

// PERUSAHAAN GROUP
Route::middleware(['auth', 'authorize:perusahaan'])->prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/dashboard', [CompanyController::class, 'dashboard'])->name('dashboard');
    Route::get('/lowongan', [CompanyController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/tambah', [CompanyController::class, 'createLowongan'])->name('tambah_lowongan');
    Route::post('/lowongan', [CompanyController::class, 'storeLowongan'])->name('lowongan.store');
    Route::get('/pendaftar', [CompanyController::class, 'pendaftar'])->name('pendaftar');

});
