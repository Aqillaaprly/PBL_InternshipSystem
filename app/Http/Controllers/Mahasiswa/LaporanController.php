<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasAbsensi;
use App\Models\AktivitasFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        // Fetch only aktivitas_absensis for the authenticated mahasiswa
        $aktivitas = AktivitasAbsensi::with('foto')
            ->where('mahasiswa_id', auth()->id())
            ->get();

        return view('mahasiswa.laporan', compact('aktivitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembimbing_id' => 'required',
            'tanggal' => 'required|date',
            'jenis_aktivitas' => 'required|string',
            'catatan' => 'required|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        // Build the data and override mahasiswa_id with the authenticated user's ID
        $data = $request->only([
            'pembimbing_id', 'tanggal', 'jenis_aktivitas', 'catatan'
        ]);
        $data['mahasiswa_id'] = auth()->id();

        // Create new aktivitas_absensi record
        $aktivitas = AktivitasAbsensi::create($data);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('aktivitas_fotos', 'public');

            AktivitasFoto::create([
                'aktivitas_absensi_id' => $aktivitas->id,
                'path' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function destroy($id)
    {
        $aktivitas = AktivitasAbsensi::findOrFail($id);

        // Delete the first associated foto file and record
        if ($aktivitas->foto->isNotEmpty()) {
            $firstFoto = $aktivitas->foto->first();
            Storage::disk('public')->delete($firstFoto->path);
            $firstFoto->delete();
        }

        $aktivitas->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
