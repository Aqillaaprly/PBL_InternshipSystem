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
        $query = \App\Models\AktivitasMagang::where('mahasiswa_id', auth()->id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('deskripsi_kegiatan', 'like', "%$search%");
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
            'tanggal' => 'required|date',
            'deskripsi_kegiatan' => 'required|string',
            'bukti_kegiatan' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['tanggal', 'deskripsi_kegiatan']);
        $data['mahasiswa_id'] = auth()->id();

        if ($request->hasFile('bukti_kegiatan')) {
            $path = $request->file('bukti_kegiatan')->store('aktifitas_magang', 'public');
            $data['bukti_kegiatan'] = $path;
        }

        \App\Models\AktivitasMagang::create($data);

        return redirect()->back()->with('success', 'Aktivitas berhasil disimpan.');
    }

    public function destroy($id)
    {
        $aktivitas = \App\Models\AktivitasMagang::findOrFail($id);

        if ($aktivitas->bukti_kegiatan) {
            Storage::disk('public')->delete($aktivitas->bukti_kegiatan);
        }

        $aktivitas->delete();

        return redirect()->back()->with('success', 'Aktivitas berhasil dihapus.');
    }
}
