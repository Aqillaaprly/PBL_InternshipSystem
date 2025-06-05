<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\DokumenPendaftar; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import
use Illuminate\Support\Facades\Response; // Pastikan ini di-import

class PendaftarController extends Controller
{
    // Metode ini menampilkan daftar pendaftar yang memenuhi kriteria (dokumen valid & status Ditinjau)
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            // Mengarahkan jika profil perusahaan tidak ditemukan.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Mengakses relasi lowongans (plural) dari Company model.
        // Ini akan mengembalikan Koleksi (bahkan jika kosong), jadi pluck() tidak akan pada null.
        $lowonganIds = $company->lowongans->pluck('id')->toArray(); // Pastikan company->lowongans tidak null

        // Jika tidak ada lowongan yang ditemukan untuk perusahaan ini,
        // maka tidak akan ada pendaftar yang diambil.
        if (empty($lowonganIds)) {
            $pendaftars = Pendaftar::whereRaw('1 = 0')->paginate(10); // Mengembalikan paginator kosong
            // Filter lowongan untuk dropdown (jika tidak ada lowongan, tetap berikan koleksi kosong)
            $lowonganPerusahaan = collect();
            return view('perusahaan.pendaftar', compact('pendaftars', 'lowonganPerusahaan'));
        }

        $query = Pendaftar::whereIn('lowongan_id', $lowonganIds)
                              ->whereHas('dokumenPendaftars', function ($q) {
                                  // Hanya pendaftar dengan setidaknya satu dokumen yang 'Valid'
                                  $q->where('status_validasi', 'Valid');
                              })
                              ->where('status_lamaran', 'Ditinjau') // Hanya pendaftar dengan status 'Ditinjau'
                              // Eager load relasi yang dibutuhkan: mahasiswa dan user-nya, lowongan, dan dokumenPendaftars
                              ->with(['mahasiswa.user', 'lowongan', 'dokumenPendaftars']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Cari berdasarkan nama mahasiswa (melalui relasi mahasiswa.user)
                $q->whereHas('mahasiswa.user', function ($q_mhs_user) use ($searchTerm) {
                    $q_mhs_user->where('name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('username', 'like', '%' . $searchTerm . '%'); // Atau username/NIM
                })
                // Atau cari berdasarkan judul lowongan
                ->orWhereHas('lowongan', function ($q_lowongan) use ($searchTerm) {
                    $q_lowongan->where('judul', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Filter berdasarkan ID lowongan (jika ada di request)
        if ($request->filled('filter_lowongan_id')) {
            $query->where('lowongan_id', $request->filter_lowongan_id);
        }

        $pendaftars = $query->latest('tanggal_daftar')->paginate(10)->withQueryString();

        // Ambil daftar lowongan perusahaan untuk filter dropdown
        $lowonganPerusahaan = Lowongan::where('company_id', $company->id)->orderBy('judul')->get();

        return view('perusahaan.pendaftar', compact('pendaftars', 'lowonganPerusahaan'));
    }

    // Metode ini untuk menampilkan dokumen tunggal
    public function showDokumen(DokumenPendaftar $dokumen)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            abort(403, 'Anda tidak terasosiasi dengan perusahaan mana pun.');
        }

        // Muat relasi pendaftar.lowongan untuk memeriksa company_id
        $dokumen->load('pendaftar.lowongan');

        // Periksa apakah dokumen ini terkait dengan pendaftar yang lowongannya dimiliki perusahaan yang sedang login
        if (!$dokumen->pendaftar || !$dokumen->pendaftar->lowongan || $dokumen->pendaftar->lowongan->company_id !== $company->id) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        // Melayani file
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            return response()->file(Storage::disk('public')->path($dokumen->file_path));
        } else {
            abort(404, 'Dokumen tidak ditemukan.');
        }
    }

    // Metode ini untuk memperbarui status lamaran pendaftar
    public function updateStatus(Request $request, Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        // Muat relasi lowongan
        $pendaftar->load('lowongan');

        if (!$company || !$pendaftar->lowongan || $pendaftar->lowongan->company_id !== $company->id) {
            abort(403, 'Anda tidak memiliki izin untuk memperbarui pendaftar ini.');
        }

        $request->validate([
            'status_lamaran' => ['required', 'string', 'in:Diterima,Ditolak,Wawancara,Ditinjau'],
        ]);

        $pendaftar->update([
            'status_lamaran' => $request->status_lamaran,
        ]);

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    // Metode ini untuk menampilkan detail pendaftar (jika Anda memiliki halaman detail terpisah)
    public function show(Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Memverifikasi bahwa pendaftar ini melamar di lowongan milik perusahaan yang sedang login
        if ($pendaftar->lowongan->company_id !== $company->id) {
            return redirect()->route('perusahaan.pendaftar.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pendaftar ini.');
        }

        // Muat semua relasi yang diperlukan untuk halaman detail pendaftar
        $pendaftar->load([
            'mahasiswa.user', // Muat mahasiswa dan user-nya
            'lowongan.company',
            'dokumenPendaftars' // Muat dokumen
        ]);

        return view('perusahaan.pendaftar.show', compact('pendaftar', 'company'));
    }
}