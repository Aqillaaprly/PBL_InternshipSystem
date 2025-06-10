<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Used in the index method
use App\Models\BimbinganMagang;

class MahasiswaBimbinganController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $bimbingans = BimbinganMagang::with(['mahasiswa.detailMahasiswa'])
        ->when($search, function ($query, $search) {
            $query->whereHas('mahasiswa.detailMahasiswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('dosen.data_mahasiswabim', compact('bimbingans'));
}
    public function show($id)
    {
        $mahasiswa = User::with('detailMahasiswa')->findOrFail($id);

        // This view needs to exist at: resources/views/dosen/mahasiswa_bimbingan/show.blade.php
        return view('dosen.showdataM', compact('mahasiswa'));
    }
}
