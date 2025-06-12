<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
//admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\LowonganController as AdminLowonganController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AktivitasController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController; // Di-alias sebagai AdminCompanyController
use App\Http\Controllers\Admin\PembimbingController as AdminPembimbingController;
use App\Http\Controllers\Admin\PendaftarController as AdminPendaftarController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AktivitasMagangController;
use App\Http\Controllers\Admin\BimbinganController;
//Company
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\PendaftarController; // Ini controller untuk dashboard Perusahaan (Role)
use App\Http\Controllers\Company\ProfilePerusahaanController;
//Dosen
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\MahasiswaBimbinganController;
use App\Http\Controllers\Dosen\AbsensiMahasiswaBimbingan;
use App\Http\Controllers\Dosen\ProfileController;
use App\Http\Controllers\Dosen\LogBimbingan;
//Mahasiswa
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Mahasiswa\LowonganController as MahasiswaLowonganController;
use App\Http\Controllers\Mahasiswa\PendaftarController as MahasiswaPendaftarController;
use App\Http\Controllers\Mahasiswa\LaporanController as MahasiswaLaporanController;
use App\Http\Controllers\Mahasiswa\CompanyController as MahasiswaCompanyController;
use App\Http\Controllers\Mahasiswa\SurveyController;
use App\Models\Company;
use App\Models\AktivitasAbsensi;

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

    Route::post('/bimbingan', [BimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create');
    Route::get('/bimbingan/{bimbinganMagang}/edit', [BimbinganController::class, 'edit'])->name('bimbingan.edit'); // NEW
    Route::put('/bimbingan/{bimbinganMagang}', [BimbinganController::class, 'update'])->name('bimbingan.update'); // NEW
    Route::delete('/bimbingan/{bimbinganMagang}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy'); // NEW
});

// DOSEN GROUP
Route::middleware(['auth', 'authorize:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

    // Route untuk melihat daftar mahasiswa bimbingan
    Route::get('/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('data_mahasiswabim');
    Route::get('/mahasiswa-bimbingan/{id}', [MahasiswaBimbinganController::class, 'show'])->name('mahasiswa.show');
    Route::get('/log-bimbingan', [LogBimbingan::class, 'index'])->name('data_log');
    Route::get('/log-bimbingan/{id}', [LogBimbingan::class, 'show'])->name('data_log.show');
    Route::get('/log-bimbingan/add/{id}', [LogBimbingan::class, 'create'])->name('log_bimbingan.create');
    Route::post('/log-bimbingan/store/{id}', [LogBimbingan::class, 'store'])->name('log_bimbingan.store');
    Route::get('/absensi', [AbsensiMahasiswaBimbingan::class, 'index'])->name('absensi.index');
    Route::get('/absensi/{id}', [AbsensiMahasiswaBimbingan::class, 'show'])->name('absensi.show');
    // Profile Management Routes
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.dosenProfile2'); // Changed name to match your controller's comment and redirect
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit3');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update3');
});

// MAHASISWA GROUP - Updated Routes Section
Route::middleware(['auth', 'authorize:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');

    // Pembimbing
    Route::get('/pembimbing', [MahasiswaController::class, 'lihatPembimbing'])->name('pembimbing');

    // Absensi
    Route::get('/absensi', fn() => view('mahasiswa.absensi'))->name('absensi');

    // Job - Updated to use controller method
    Route::get('/job', [MahasiswaController::class, 'job'])->name('job');

    // Profile Routes
    Route::get('/profile', [MahasiswaProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [MahasiswaProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [MahasiswaProfileController::class, 'update'])->name('profile.update');

    // Perusahaan Routes - UPDATED
    Route::get('/perusahaan', [MahasiswaCompanyController::class, 'perusahaan'])->name('perusahaan.index');
    Route::get('/perusahaan/{company}', [MahasiswaCompanyController::class, 'showProfile'])
        ->name('perusahaan.show');

    // Laporan Routes with Search
    Route::get('/laporan', [MahasiswaLaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [MahasiswaLaporanController::class, 'store'])->name('laporan.store');
    Route::delete('/laporan/{id}', [MahasiswaLaporanController::class, 'destroy'])->name('laporan.destroy');

    // Lowongan Routes with Search/Filter
    Route::get('/lowongan', [MahasiswaLowonganController::class, 'index'])->name('lowongan.index');
    Route::get('/lowongan/create', [MahasiswaLowonganController::class, 'create'])->name('lowongan.create');
    Route::post('/lowongan', [MahasiswaLowonganController::class, 'store'])->name('lowongan.store');
    Route::get('/lowongan/{lowongan}', [MahasiswaLowonganController::class, 'show'])->name('lowongan.show');
    Route::get('/lowongan/{lowongan}/edit', [MahasiswaLowonganController::class, 'edit'])->name('lowongan.edit');
    Route::put('/lowongan/{lowongan}', [MahasiswaLowonganController::class, 'update'])->name('lowongan.update');
    Route::delete('/lowongan/{lowongan}', [MahasiswaLowonganController::class, 'destroy'])->name('lowongan.destroy');

    // Pendaftar Routes
    Route::get('/pendaftar', [MahasiswaPendaftarController::class, 'showPendaftaranTable'])->name('pendaftar');
    Route::get('/pendaftar/form', [MahasiswaPendaftarController::class, 'showPendaftaranForm'])->name('pendaftar.form');
    Route::post('/pendaftar/submit', [MahasiswaPendaftarController::class, 'submitPendaftaran'])->name('pendaftar.submit');
    Route::delete('/pendaftar/{pendaftar}', [MahasiswaPendaftarController::class, 'cancelPendaftaran'])->name('pendaftar.cancel');
    Route::get('/apply-from-lowongan/{lowonganId}', [MahasiswaPendaftarController::class, 'applyFromLowongan'])
        ->name('apply.from.lowongan');
    Route::get('/pendaftar/dokumen/{pendaftarId}', [MahasiswaPendaftarController::class, 'showDocuments'])
        ->name('pendaftar.dokumen');

    // Survey Routes - Updated and consolidated
    Route::get('/survey', [SurveyController::class, 'index'])->name('survey');
    Route::post('/survey/process', [SurveyController::class, 'process'])->name('survey.process');
    Route::post('/survey/accept', [SurveyController::class, 'accept'])->name('survey.accept');
    Route::post('/survey/cancel', [SurveyController::class, 'cancelRecommendation'])->name('survey.cancel');
});

// PERUSAHAAN GROUP (Ini untuk DASHBOARD ROLE PERUSAHAAN, bukan manajemen oleh ADMIN)
Route::middleware(['auth', 'authorize:perusahaan'])->prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/show', [CompanyController::class, 'show']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Show Management Routes
    Route::get('/profil', [CompanyController::class, 'show'])->name('profile.perusahaanProfile1');
    Route::get('/profil/edit', [CompanyController::class, 'edit'])->name('profile.edit1');
    Route::put('/profil', [CompanyController::class, 'update'])->name('profile.update1');

    // Profile Management Routes
    Route::get('/profil', [ProfilePerusahaanController::class, 'show'])->name('profile.perusahaanProfile2'); // Changed name to match your controller's comment and redirect
    Route::get('/profil/edit', [ProfilePerusahaanController::class, 'edit'])->name('profile.edit2');
    Route::put('/profil', [ProfilePerusahaanController::class, 'update'])->name('profile.update2');

    // Manajemen Lowongan
    // Rute Index Lowongan (daftar lowongan)
    Route::get('/lowongan', [CompanyController::class, 'lowongan'])->name('lowongan');
    // Rute Menambah Lowongan
    Route::get('/lowongan/tambah', [CompanyController::class, 'createLowongan'])->name('tambah_lowongan');
    Route::post('/lowongan', [CompanyController::class, 'storeLowongan'])->name('lowongan.store');

    // Rute Detail Lowongan
    Route::get('/lowongan/{lowongan}', [CompanyController::class, 'showLowongan'])->name('lowongan.show'); // Rute Show
    // Rute Edit Lowongan
    Route::get('/lowongan/{lowongan}/edit', [CompanyController::class, 'editLowongan'])->name('lowongan.edit');
    // Rute Update Lowongan
    Route::put('/lowongan/{lowongan}', [CompanyController::class, 'updateLowongan'])->name('lowongan.update');
    // Rute Hapus Lowongan
    Route::delete('/lowongan/{lowongan}', [CompanyController::class, 'destroyLowongan'])->name('lowongan.destroy');

    // Pendaftar Management

    Route::patch('/pendaftar/{pendaftar}/update-status-lamaran', [PendaftarController::class, 'updateStatusLamaran'])->name('pendaftar.updateStatusLamaran');
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/{pendaftar}', [PendaftarController::class, 'show'])->name('pendaftar.detail');
    Route::get('/pendaftar/{pendaftar}/dokumen', [PendaftarController::class, 'showDokumen'])->name('pendaftar.showDokumen');
    Route::patch('/pendaftar/{pendaftar}/dokumen/{dokumenPendaftar}/update-status', [PendaftarController::class, 'updateStatusDokumen'])->name('pendaftar.dokumen.updateStatus');

    // Activities
    Route::get('/aktivitas_magang', [App\Http\Controllers\Company\AktivitasMagangController::class, 'index'])->name('aktivitas_magang');
    Route::get('/aktivitas-mahasiswa/{mahasiswa_id}', [AktivitasMagangController::class, 'show'])->name('aktivitas_magang.show'); // Halaman detail kegiatan
    Route::post('/aktivitas-mahasiswa/{id}/verify', [AktivitasMagangController::class, 'verify'])->name('aktivitas_magang.verify'); // Untuk verifikasi aktivitas
});
