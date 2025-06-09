<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasAbsensi;
use App\Models\AktivitasFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = AktivitasAbsensi::with('foto')
            ->where('mahasiswa_id', auth()->id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis_aktivitas', 'like', "%$search%")
                    ->orWhere('catatan', 'like', "%$search%");
            });
        }

        if ($request->has('filter_date')) {
            $query->whereDate('tanggal', $request->filter_date);
        }

        $aktivitas = $query->orderBy('tanggal', 'desc')->paginate(10);

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

        $data = $request->only([
            'pembimbing_id', 'tanggal', 'jenis_aktivitas', 'catatan'
        ]);
        $data['mahasiswa_id'] = auth()->id();

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

        if ($aktivitas->foto->isNotEmpty()) {
            $firstFoto = $aktivitas->foto->first();
            Storage::disk('public')->delete($firstFoto->path);
            $firstFoto->delete();
        }

        $aktivitas->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}
