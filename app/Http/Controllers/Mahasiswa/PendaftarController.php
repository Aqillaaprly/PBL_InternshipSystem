<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Lowongan;
use App\Models\DokumenPendaftar;
use Illuminate\Support\Facades\Storage;

class PendaftarController extends Controller
{
    public function showPendaftaranForm(Request $request)
    {
        $query = Lowongan::with('company')
            ->where('status', 'Aktif')
            ->where('tanggal_tutup', '>=', now());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('lokasi', 'like', "%$search%")
                    ->orWhereHas('company', function($q) use ($search) {
                        $q->where('nama_perusahaan', 'like', "%$search%");
                    });
            });
        }

        $lowongans = $query->paginate(10);

        $pendaftarans = Pendaftar::with('lowongan.company', 'dokumenPendaftars')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $prefilledLowonganId = $request->query('lowongan_id');

        return view('mahasiswa.pendaftar', [
            'lowongans' => $lowongans,
            'pendaftarans' => $pendaftarans,
            'selectedLowonganId' => $prefilledLowonganId
        ]);
    }

    public function submitPendaftaran(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongans,id',
            'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'portofolio' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
            'khs_transkrip' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ktm' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'surat_izin_ortu' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'pakta_integritas' => 'required|file|mimes:pdf,doc,docx|max:5000',
            'sertifikat_kompetensi' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
            'sktm_kip' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
            'catatan_pendaftar' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->back()->withErrors('Anda harus login terlebih dahulu.');
        }

        // Check if already applied
        $existing = Pendaftar::where('user_id', $userId)
            ->where('lowongan_id', $request->lowongan_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah mendaftar untuk lowongan ini.');
        }

        // Create pendaftar record
        $pendaftar = Pendaftar::create([
            'user_id' => $userId,
            'lowongan_id' => $request->lowongan_id,
            'tanggal_daftar' => now(),
            'status_lamaran' => 'Pending',
            'catatan_pendaftar' => $request->catatan_pendaftar,
            'catatan_admin' => null,
        ]);

        // Define document types and their field names
        $documentTypes = [
            'surat_lamaran' => 'Surat Lamaran',
            'cv' => 'Daftar Riwayat Hidup (CV)',
            'portofolio' => 'Portofolio',
            'khs_transkrip' => 'KHS atau Transkrip Nilai',
            'ktp' => 'KTP',
            'ktm' => 'KTM',
            'surat_izin_ortu' => 'Surat Izin Orang Tua',
            'pakta_integritas' => 'Pakta Integritas',
            'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
            'sktm_kip' => 'SKTM atau KIP Kuliah'
        ];

        // Save each document
        foreach ($documentTypes as $field => $name) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store("dokumen_pendaftar/{$pendaftar->id}", 'public');

                DokumenPendaftar::create([
                    'pendaftar_id' => $pendaftar->id,
                    'nama_dokumen' => $name,
                    'file_path' => $path,
                    'status_validasi' => 'Belum Diverifikasi',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function showDocuments($pendaftarId)
    {
        $pendaftar = Pendaftar::with('dokumenPendaftars')
            ->where('user_id', Auth::id())
            ->findOrFail($pendaftarId);

        return view('mahasiswa.dokumen_pendaftar', ['dokumen' => $pendaftar->dokumenPendaftars]);
    }

    public function applyFromLowongan($lowonganId)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $existing = Pendaftar::where('user_id', $userId)
            ->where('lowongan_id', $lowonganId)
            ->first();

        if ($existing) {
            return redirect()->route('mahasiswa.pendaftar')->with('error', 'Anda sudah mendaftar untuk lowongan ini.');
        }

        return redirect()->route('mahasiswa.pendaftar', ['lowongan_id' => $lowonganId]);
    }
}
