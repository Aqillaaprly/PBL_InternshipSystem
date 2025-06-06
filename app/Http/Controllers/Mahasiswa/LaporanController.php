<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\BimbinganMagang;
use App\Models\BimbinganFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        // Only fetch records for the authenticated mahasiswa
        $bimbingans = BimbinganMagang::with('foto')
            ->where('mahasiswa_id', auth()->id())
            ->get();

        return view('mahasiswa.laporan', compact('bimbingans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembimbing_id' => 'required',
            'tanggal' => 'required|date',
            'jenis_bimbingan' => 'required|string',
            'catatan' => 'required|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Build the data and override mahasiswa_id with the authenticated user's ID
        $data = $request->only([
            'pembimbing_id', 'tanggal', 'jenis_bimbingan', 'catatan'
        ]);
        $data['mahasiswa_id'] = auth()->id(); // Ensure mahasiswa_id is always set correctly

        $bimbingan = BimbinganMagang::create($data);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('bimbingan_fotos', 'public');
            BimbinganFoto::create([
                'bimbingan_id' => $bimbingan->id,
                'path' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function destroy($id)
    {
        BimbinganMagang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
