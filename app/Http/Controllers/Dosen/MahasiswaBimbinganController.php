<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BimbinganMagang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbinganController extends Controller
{
    /**
     * Menampilkan daftar mahasiswa bimbingan untuk dosen yang sedang login.
     */
   public function index(Request $request)
{
    $query = User::whereHas('role', fn($q) => $q->where('name', 'mahasiswa'));

    if ($search = $request->input('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
        });
    }

    $mahasiswas = $query->with('detailMahasiswa')->paginate(10);

    return view('dosen.data_mahasiswabim', compact('mahasiswas'));
}

    /**
     * Menampilkan detail mahasiswa tertentu (show).
     */
    public function show($id)
    {
        $mahasiswa = User::with('detailMahasiswa')->findOrFail($id);

        return view('dosen.mahasiswa_bimbingan.show', compact('mahasiswa'));
    }
}