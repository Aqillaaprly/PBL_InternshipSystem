<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController; // Di-alias sebagai AdminCompanyController
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\PenugasanPembimbingController;
use App\Http\Controllers\Company\CompanyController; // Ini controller untuk dashboard Perusahaan (Role)
use App\Http\Controllers\Dosen\MahasiswaBimbinganController; //dosen 
use App\Http\Controllers\Dosen\AbsensiMahasiswaController; //dosen
use App\Http\Controllers\Dosen\LogBimbingan; //dosen

// Mengarahkan halaman utama ('/') ke halaman login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');

// LOGIN dan LOGOUT Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('log-in');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ADMIN GROUP
Route::middleware(['auth', 'authorize:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('lowongan', AdminLowonganController::class);
    Route::resource('pendaftar', AdminPendaftarController::class);

    Route::get('/perusahaan', [AdminCompanyController::class, 'index'])->name('perusahaan.index');
    Route::get('/perusahaan/create', [AdminCompanyController::class, 'create'])->name('perusahaan.create');
    Route::post('/perusahaan', [AdminCompanyController::class, 'store'])->name('perusahaan.store');
    Route::get('/perusahaan/{companyId}', [AdminCompanyController::class, 'show'])->name('perusahaan.show'); // Gunakan {companyId}
    Route::get('/perusahaan/{companyId}/edit', [AdminCompanyController::class, 'edit'])->name('perusahaan.edit'); // Gunakan {companyId}
    Route::put('/perusahaan/{companyId}', [AdminCompanyController::class, 'update'])->name('perusahaan.update'); // Gunakan {companyId}
    Route::delete('/perusahaan/{companyId}', [AdminCompanyController::class, 'destroy'])->name('perusahaan.destroy'); // Gunakan {companyId}


    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::get('/data-mahasiswa/create', [AdminMahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/data-mahasiswa', [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/data-mahasiswa/{mahasiswa}/show', [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/data-mahasiswa/{mahasiswa}/edit', [AdminMahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    Route::get('/data-pembimbing', [AdminPembimbingController::class, 'index'])->name('data_pembimbing');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');


    // Manajemen Penugasan Pembimbing
    Route::resource('penugasan-pembimbing', PenugasanPembimbingController::class);
});

// DOSEN GROUP
Route::middleware(['auth', 'authorize:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dosen.dashboard');
    })->name('dashboard');
    // Route untuk melihat daftar mahasiswa bimbingan
    Route::get('/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('data_mahasiswabim');
    Route::get('/mahasiswa-bimbingan/{id}', [MahasiswaBimbinganController::class, 'show'])->name('mahasiswa.show');
    Route::get('/log-bimbingan', [LogBimbingan::class, 'index'])->name('data_log');
    Route::get('/log-bimbingan/{id}', [LogBimbingan::class, 'show'])->name('data_log.show');
});



// MAHASISWA GROUP
Route::middleware(['auth', 'authorize:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('mahasiswa.dashboard');
    })->name('dashboard');

    Route::get('/absensi', function () {
        return view('mahasiswa.absensi');
    })->name('absensi');
    Route::get('/job', function () {
        return view('mahasiswa.job');
    })->name('job');
    Route::get('/profile', function () {
        return view('mahasiswa.mahasiswaProfile');
    })->name('profile');
    Route::get('/perusahaan', function () {
        return view('mahasiswa.perusahaan');
    })->name('perusahaan');
    // Tambahkan route mahasiswa lainnya di sini
});

// PERUSAHAAN GROUP (Ini untuk DASHBOARD ROLE PERUSAHAAN, bukan manajemen oleh ADMIN)
Route::middleware(['auth', 'authorize:perusahaan'])->prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/dashboard', [CompanyController::class, 'dashboard'])->name('dashboard');
    Route::get('/lowongan', [CompanyController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/tambah', [CompanyController::class, 'createLowongan'])->name('tambah_lowongan');
    Route::post('/lowongan', [CompanyController::class, 'storeLowongan'])->name('lowongan.store');
    Route::get('/pendaftar', [CompanyController::class, 'pendaftar'])->name('pendaftar');
});