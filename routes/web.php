<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// LOGIN
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('log-in');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ADMIN GROUP
Route::middleware(['auth', 'authorize:admin'])->prefix('admin')->group(function () {
    
    Route::get('dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/userdata', [UserController::class, 'view'])->name('userdata.index');
    Route::get('/userdata/create', [UserController::class, 'create'])->name('userdata.create');
    Route::post('/userdata', [UserController::class, 'store'])->name('userdata.store');
    Route::get('/userdata/{id}/edit', [UserController::class, 'edit'])->name('userdata.edit');
    Route::put('/userdata/{id}', [UserController::class, 'update'])->name('userdata.update');
    Route::delete('/userdata/{id}', [UserController::class, 'destroy'])->name('userdata.destroy');
});

// Other roles
Route::middleware(['auth', 'authorize:admin'])->get('/admin/dashboard', fn() => view('admin.dashboard'));
Route::middleware(['auth', 'authorize:dosen'])->get('/dosen/dashboard', fn() => view('dosen.dashboard'));
Route::middleware(['auth', 'authorize:mahasiswa'])->get('/mahasiswa/dashboard', fn() => view('mahasiswa.dashboard'));
Route::middleware(['auth', 'authorize:perusahaan'])->get('/perusahaan/dashboard', fn() => view('perusahaan.dashboard'));
    
//User