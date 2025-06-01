<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Company; // Pastikan model Company di-import
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Menampilkan dasbor perusahaan.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $company = $user->company; // Mengambil data perusahaan terkait pengguna yang login

        if (!$company) {
            // Jika pengguna perusahaan tidak memiliki entitas perusahaan terkait,
            // redirect atau tampilkan error.
            // Ini seharusnya tidak terjadi jika data di-seed dengan benar.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil statistik dasar untuk dasbor perusahaan
        $jumlahLowonganAktif = Lowongan::where('company_id', $company->id)
                               ->where('status', 'Aktif') // Hanya lowongan dengan status 'Aktif'
                               ->count();
$jumlahTotalPendaftar = Pendaftar::whereHas('lowongan', function ($query) use ($company) {
                                    $query->where('company_id', $company->id); // Pendaftar untuk lowongan milik perusahaan ini
                                })->count();

        // Mengubah nama view yang dipanggil
        return view('perusahaan.dashboard', compact('company', 'jumlahLowonganAktif', 'jumlahTotalPendaftar'));
    }

    /**
     * Menampilkan daftar lowongan milik perusahaan.
     */
    public function lowongan()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $lowongans = Lowongan::where('company_id', $company->id)
                             ->latest()
                             ->paginate(10); // Menggunakan paginasi

        // Pastikan view ini juga konsisten jika Anda mengubah struktur folder
        // Jika view lowongan ada di resources/views/perusahaan/lowongan.blade.php
        // maka panggilannya menjadi view('perusahaan.lowongan', ...)
        return view('perusahaan.lowongan', compact('company', 'lowongans'));
    }

    /**
     * Menampilkan form untuk menambah lowongan baru.
     */
    public function createLowongan()
    {
        // Pastikan view ini juga konsisten
        // Jika view tambah lowongan ada di resources/views/perusahaan/tambah_lowongan.blade.php
        return view('perusahaan.tambah_lowongan');
    }

    /**
     * Menyimpan lowongan baru yang dibuat oleh perusahaan.
     */
    public function storeLowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tipe' => 'required|in:Penuh Waktu,Paruh Waktu,Kontrak,Internship',
            'lokasi' => 'required|string|max:255',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0|gte:gaji_min',
            'tanggal_tutup' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return redirect()->route('perusahaan.tambah_lowongan')
                        ->withErrors($validator)
                        ->withInput();
        }

        Lowongan::create([
            'company_id' => $company->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kualifikasi' => $request->kualifikasi,
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'gaji_min' => $request->gaji_min,
            'gaji_max' => $request->gaji_max,
            'tanggal_buka' => Carbon::now()->toDateString(),
            'tanggal_tutup' => $request->tanggal_tutup,
            'status' => 'Aktif',
        ]);

        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Menampilkan daftar pendaftar untuk lowongan perusahaan.
     */
    public function pendaftar(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $lowonganIds = Lowongan::where('company_id', $company->id)->pluck('id');

        $query = Pendaftar::with(['user', 'lowongan'])
                           ->whereIn('lowongan_id', $lowonganIds);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%");
            })->orWhereHas('lowongan', function ($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('filter_lowongan_id')) {
            $query->where('lowongan_id', $request->filter_lowongan_id);
        }

        $pendaftars = $query->latest('tanggal_daftar')->paginate(10);
        $lowonganPerusahaan = Lowongan::where('company_id', $company->id)->orderBy('judul')->get();

        // Pastikan view ini juga konsisten
        // Jika view pendaftar ada di resources/views/perusahaan/pendaftar.blade.php
        return view('perusahaan.pendaftar', compact('company', 'pendaftars', 'lowonganPerusahaan'));
    }

    /**
     * Memperbarui status pendaftar (Contoh).
     */
    public function updateStatusPendaftar(Request $request, Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || $pendaftar->lowongan->company_id !== $company->id) {
            return redirect()->route('perusahaan.pendaftar')->with('error', 'Aksi tidak diizinkan.');
        }

        $validator = Validator::make($request->all(), [
            'status_lamaran' => 'required|in:Pending,Ditinjau,Wawancara,Diterima,Ditolak',
            'catatan_perusahaan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $pendaftar->status_lamaran = $request->status_lamaran;
        if ($request->filled('catatan_perusahaan')) {
            // $pendaftar->catatan_perusahaan = $request->catatan_perusahaan;
        }
        $pendaftar->save();

        return redirect()->route('perusahaan.pendaftar')->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
