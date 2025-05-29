<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController; // Untuk manajemen user data
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController; // Alias untuk kejelasan
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
    // Sebaiknya UserController ini ada di namespace Admin jika khusus untuk admin
    // Jika UserController digunakan general, pastikan ada proteksi role di dalamnya atau pisahkan
    Route::get('/users', [UserController::class, 'view'])->name('users.index'); // Mengganti 'userdata' menjadi 'users' untuk konsistensi
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Manajemen Perusahaan oleh Admin
    Route::resource('perusahaan', AdminCompanyController::class)->except(['destroy']); // Menggunakan resource controller
    Route::delete('perusahaan/{perusahaan}', [AdminCompanyController::class, 'destroy'])->name('perusahaan.destroy'); // Route delete terpisah jika ada konfirmasi khusus

    // Manajemen Lowongan oleh Admin
    Route::resource('lowongan', AdminLowonganController::class)->except(['destroy']);
    Route::delete('lowongan/{lowongan}', [AdminLowonganController::class, 'destroy'])->name('lowongan.destroy');


    // Manajemen Pendaftar oleh Admin
    Route::resource('pendaftar', AdminPendaftarController::class)->except(['destroy']);
     Route::delete('pendaftar/{pendaftar}', [AdminPendaftarController::class, 'destroy'])->name('pendaftar.destroy');


    // Data Mahasiswa (dikelola Admin)
    Route::get('/data-mahasiswa', [AdminMahasiswaController::class, 'index'])->name('datamahasiswa');
    // CRUD untuk mahasiswa oleh admin bisa ditambahkan di sini jika diperlukan

    // Data Pembimbing (dikelola Admin)
    Route::get('/data-pembimbing', [AdminPembimbingController::class, 'index'])->name('data_pembimbing');
    // CRUD untuk pembimbing oleh admin bisa ditambahkan di sini

    // Laporan (dikelola Admin)
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan');

    // Profil Admin
    Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit'); // Tambahkan route untuk edit profile
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update'); // Tambahkan route untuk update profile

    // Contoh route jika ada halaman pengaturan khusus admin
    // Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan');
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
        // Contoh jika mahasiswa dashboard butuh data spesifik
        // $user = Auth::user();
        // $pendaftaranSaya = Pendaftar::where('user_id', $user->id)->with('lowongan.company')->get();
        // return view('mahasiswa.dashboard', compact('user', 'pendaftaranSaya'));
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
        // $company = Auth::user()->company; // Asumsi ada relasi company di model User perusahaan
        // $lowongans = Lowongan::where('company_id', $company->id)->get();
        // $pendaftars = Pendaftar::whereIn('lowongan_id', $lowongans->pluck('id'))->count();
        // return view('company.dashboard', compact('company', 'lowongans', 'pendaftars'));
        return view('company.dashboard'); // Pastikan view ini ada: resources/views/company/dashboard.blade.php
    })->name('dashboard');

    Route::get('/lowongan', function () { return view('company.lowongan'); })->name('lowongan');
    Route::get('/pendaftar', function () { return view('company.pendaftar'); })->name('pendaftar');
    Route::get('/tambah-lowongan', function () { return view('company.tambah_lowongan'); })->name('tambah_lowongan');
    // Proses tambah lowongan dan pendaftar sebaiknya menggunakan Controller
    // Route::post('/lowongan', [CompanyLowonganController::class, 'store'])->name('lowongan.store');
    // Route::post('/pendaftar/update-status', [CompanyPendaftarController::class, 'updateStatus'])->name('pendaftar.update_status');
});