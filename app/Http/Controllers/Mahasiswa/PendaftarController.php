<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Lowongan;

class PendaftarController extends Controller
{
    // Show form and list of user's applications
    public function showPendaftaranForm()
    {
        $lowongans = Lowongan::with('company')->get();

        $pendaftarans = Pendaftar::with('lowongan')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('mahasiswa.pendaftar', compact('lowongans', 'pendaftarans'));
    }

    // Manual form-based submission
    public function submitPendaftaran(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongans,id',
            'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
            'catatan_pendaftar' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back()->withErrors('Anda harus login terlebih dahulu.');
        }

        $data = [
            'user_id' => $userId,
            'lowongan_id' => $request->lowongan_id,
            'tanggal_daftar' => now(),
            'status_lamaran' => 'Pending',
            'catatan_pendaftar' => $request->catatan_pendaftar,
            'catatan_admin' => null,
        ];

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

    // 🆕 New method for quick apply from lowongan list
    public function applyFromLowongan($lowonganId)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if already applied
        $existing = Pendaftar::where('user_id', $userId)
            ->where('lowongan_id', $lowonganId)
            ->first();

        if ($existing) {
            return redirect()->route('mahasiswa.pendaftar')->with('error', 'Anda sudah mendaftar untuk lowongan ini.');
        }

        // Create pendaftaran with placeholder file paths
        Pendaftar::create([
            'user_id' => $userId,
            'lowongan_id' => $lowonganId,
            'tanggal_daftar' => now(),
            'status_lamaran' => 'Pending',
            'catatan_pendaftar' => null,
            'catatan_admin' => null,
            'surat_lamaran_path' => 'dokumen/surat_lamaran/default.pdf',
            'cv_path' => 'dokumen/cv/default.pdf',
            'portofolio_path' => null,
        ]);

        return redirect()->route('mahasiswa.pendaftar')->with('success', 'Berhasil mendaftar ke lowongan.');
    }
}
