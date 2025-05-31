<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembimbing; // Ganti User dengan Pembimbing
// Role mungkin tidak lagi dibutuhkan di sini jika semua dosen ada di tabel pembimbings
// use App\Models\Role; 

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        // Query ke model Pembimbing dan eager load data user terkait
        $query = Pembimbing::with('user');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nip', 'like', "%{$searchTerm}%") // Cari NIP di tabel pembimbings
                  ->orWhere('nama_lengkap', 'like', "%{$searchTerm}%") // Cari nama_lengkap di tabel pembimbings
                  ->orWhere('jabatan_fungsional', 'like', "%{$searchTerm}%")
                  ->orWhere('program_studi_homebase', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) { // Cari di tabel users melalui relasi
                      $userQuery->where('username', 'like', "%{$searchTerm}%") // Username login
                                ->orWhere('email', 'like', "%{$searchTerm}%");   // Email login
                  });
            });
        }

        // $pembimbings adalah koleksi dari model Pembimbing
        $pembimbings = $query->orderBy('nama_lengkap')->paginate(10)->withQueryString(); 

        return view('admin.Pembimbing.data_pembimbing', compact('pembimbings'));
    }
}