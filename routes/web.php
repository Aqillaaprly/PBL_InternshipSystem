<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AktivitasController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController; // Di-alias sebagai AdminCompanyController
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AktivitasMagangController;

use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\PendaftarController; // Ini controller untuk dashboard Perusahaan (Role)

use App\Http\Controllers\Dosen\LogBimbingan;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\UserController; // dosen
// dosen
use Illuminate\Support\Facades\Route; // dosen

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
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // Manejemen Pendaftar Lowongan
    Route::get('/pendaftar/{pendaftar}/dokumen', [AdminPendaftarController::class, 'showDokumen'])->name('pendaftar.showDokumen');
    Route::post('/pendaftar/{pendaftar}/upload-dokumen-batch', [AdminPendaftarController::class, 'uploadDokumenBatch'])->name('pendaftar.uploadDokumenBatch');
    Route::delete('/pendaftar/{pendaftar}/dokumen/{dokumenPendaftar}', [AdminPendaftarController::class, 'destroyDokumen'])->name('pendaftar.dokumen.destroy');
    Route::patch('/pendaftar/{pendaftar}/dokumen/{dokumenPendaftar}/update-status', [AdminPendaftarController::class, 'updateStatusDokumen'])->name('pendaftar.dokumen.updateStatus');
    Route::patch('/pendaftar/{pendaftar}/dokumen/update-all-status', [AdminPendaftarController::class, 'updateAllStatusDokumen'])->name('pendaftar.dokumen.updateAllStatus');

    // Manajemen Perusahaan
    Route::resource('perusahaan', AdminCompanyController::class)->parameters(['perusahaan' => 'company']);

    // Manejemen Data Mahasiswa
    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::get('/data-mahasiswa/create', [AdminMahasiswaController::class, 'create'])->name('mahasiswa.create');
    Route::post('/data-mahasiswa', [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/data-mahasiswa/{mahasiswa}/show', [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::get('/data-mahasiswa/{mahasiswa}/edit', [AdminMahasiswaController::class, 'edit'])->name('mahasiswa.edit');
    Route::put('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/data-mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Manajemen Penugasan Pembimbing (oleh Admin)
    Route::resource('pembimbings', AdminPembimbingController::class);

    // Aktivitas & Absensi Mahasiswa (Admin View Only)
    Route::get('/aktivitas-mahasiswa', [AktivitasController::class, 'index'])->name('aktivitas-mahasiswa.index'); // Tabel utama mahasiswa
    Route::get('/aktivitas-mahasiswa/{mahasiswa_id}', [AktivitasController::class, 'show'])->name('aktivitas-mahasiswa.show'); // Halaman detail kegiatan
    Route::post('/aktivitas-mahasiswa/{id}/verify', [AktivitasController::class, 'verify'])->name('aktivitas-mahasiswa.verify'); // Untuk verifikasi aktivitas


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
    Route::get('/show', [CompanyController::class, 'show']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profil', [CompanyController::class, 'show'])->name('profil');
    Route::get('/profil/edit', [CompanyController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [CompanyController::class, 'update'])->name('profil.update');

    // Lowongan Management
    Route::get('/lowongan', [CompanyController::class, 'lowongan'])->name('lowongan');
    Route::get('/lowongan/tambah', [CompanyController::class, 'createLowongan'])->name('tambah_lowongan');
    Route::post('/lowongan', [CompanyController::class, 'storeLowongan'])->name('lowongan.store');

    // Pendaftar Management

     Route::patch('/pendaftar/{pendaftar}/update-status-lamaran', [PendaftarController::class, 'updateStatusLamaran'])->name('pendaftar.updateStatusLamaran');
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/{pendaftar}', [PendaftarController::class, 'show'])->name('pendaftar.detail');
    Route::get('/pendaftar/{pendaftar}/dokumen', [PendaftarController::class, 'showDokumen'])->name('pendaftar.showDokumen');
    Route::patch('/pendaftar/{pendaftar}/dokumen/{dokumenPendaftar}/update-status', [PendaftarController::class, 'updateStatusDokumen'])->name('pendaftar.dokumen.updateStatus');

    // Activities
    Route::get('/aktivitas_magang', [CompanyController::class, 'aktivitas_magang'])->name('aktivitas_magang');
});
