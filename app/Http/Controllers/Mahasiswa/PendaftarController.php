<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Lowongan;

class PendaftarController extends Controller
{
    public function showPendaftaranForm()
    {
        $lowongans = Lowongan::with('company')->get();

        $pendaftarans = Pendaftar::with('lowongan')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.pendaftar', compact('lowongans', 'pendaftarans'));
    }

    public function submitPendaftaran(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongans,id',
            'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
            'catatan_pendaftar' => 'nullable|string|max:1000',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'lowongan_id' => $request->lowongan_id,
            'tanggal_daftar' => now(),
            'status_lamaran' => 'Pending',
            'catatan_pendaftar' => $request->catatan_pendaftar,
            'catatan_admin' => null
        ];

        // Handle file uploads
        if ($request->hasFile('surat_lamaran')) {
            $data['surat_lamaran_path'] = $request->file('surat_lamaran')->store('dokumen/surat_lamaran', 'public');
        }

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('dokumen/cv', 'public');
        }

        if ($request->hasFile('portofolio')) {
            $data['portofolio_path'] = $request->file('portofolio')->store('dokumen/portofolio', 'public');
        }

        Pendaftar::create($data);

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim.');
    }
}
