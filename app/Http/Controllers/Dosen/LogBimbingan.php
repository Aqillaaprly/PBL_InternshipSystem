<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BimbinganMagang;
use App\Models\LogBimbinganMagang;

class LogBimbingan extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $bimbingans = BimbinganMagang::with(['mahasiswa', 'pembimbing', 'company'])
        ->when($search, function ($query, $search) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('dosen.data_log', compact('bimbingans'));
}
    public function show($id)
    {
        $mahasiswa = User::with('detailMahasiswa')->findOrFail($id);
        // This view needs to exist at: resources/views/dosen/mahasiswa_bimbingan/show.blade.php
        return view('dosen.showLog', compact('mahasiswa'));
    }

   
}
