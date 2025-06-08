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
    public function showPendaftaranForm(Request $request)
    {
        $lowongans = Lowongan::with('company')->get();

        $pendaftarans = Pendaftar::with('lowongan')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $prefilledLowonganId = $request->query('lowongan_id');

        return view('mahasiswa.pendaftar', compact('lowongans', 'pendaftarans', 'prefilledLowonganId'));
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

    // 🆕 Updated method for "Apply" from lowongan — redirect with pre-filled lowongan_id
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

        // Redirect to the pendaftar form page with lowongan_id pre-filled
        return redirect()->route('mahasiswa.pendaftar', ['lowongan_id' => $lowonganId]);
    }
}
