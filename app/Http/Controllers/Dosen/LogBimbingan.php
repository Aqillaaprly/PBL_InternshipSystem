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

    $bimbinganIds = BimbinganMagang::where('mahasiswa_user_id', $id)->pluck('id');

    $logs = LogBimbinganMagang::whereIn('bimbingan_magang_id', $bimbinganIds)->get();

    return view('dosen.showLog', compact('mahasiswa', 'logs'));
}


    public function create($bimbingan_id)
    {
        $bimbingan = BimbinganMagang::with('mahasiswa')->findOrFail($bimbingan_id);

        return view('dosen.addLog', compact('bimbingan'));
    }

    public function store(Request $request, $bimbingan_id)
    {
        $request->validate([
            'metode_bimbingan' => 'required|string|max:255',
            'waktu_bimbingan' => 'required|date',
            'topik_bimbingan' => 'required|string',
            'deskripsi' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        $bimbingan = BimbinganMagang::findOrFail($bimbingan_id);

        // Simpan log bimbingan
        LogBimbinganMagang::create([
            'bimbingan_magang_id' => $bimbingan->id,
            'mahasiswa_id' => $bimbingan->mahasiswa_id,
            'metode_bimbingan' => $request->metode_bimbingan,
            'waktu_bimbingan' => $request->waktu_bimbingan,
            'topik_bimbingan' => $request->topik_bimbingan,
            'deskripsi' => $request->deskripsi,
            'nilai' => $request->nilai,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('dosen.data_log')->with('success', 'Log Bimbingan berhasil ditambahkan.');
    }

   
}
