<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// ... other necessary imports ...
use App\Models\User; // Used in the index method

class MahasiswaBimbinganController extends Controller
{
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

        // This view needs to exist at: resources/views/dosen/data_mahasiswabim.blade.php
        return view('dosen.data_mahasiswabim', compact('mahasiswas'));
    }

    public function show($id)
    {
        $mahasiswa = User::with('detailMahasiswa')->findOrFail($id);
        // This view needs to exist at: resources/views/dosen/mahasiswa_bimbingan/show.blade.php
        return view('dosen.showdataM', compact('mahasiswa'));
    }
}