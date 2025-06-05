<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Pastikan ini di-import jika digunakan

class CompanyController extends Controller
{
    // --- Metode dashboard() telah dihapus dari sini dan dipindahkan ke DashboardController ---

    /**
     * Menampilkan profil perusahaan.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }
        return view('perusahaan.show', compact('company')); // Tampilkan view profil perusahaan
    }

    /**
     * Menampilkan form untuk mengedit profil perusahaan.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }
        return view('perusahaan.edit_profil', compact('company')); // Tampilkan view edit profil
    }

    /**
     * Memperbarui profil perusahaan.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'required|string|max:20',
            'email_perusahaan' => 'required|email|max:255',
            'deskripsi' => 'nullable|string',
            'industri' => 'nullable|string|max:255', // Pastikan kolom ini ada di tabel 'companies'
            'ukuran_perusahaan' => 'nullable|string|max:255', // Pastikan kolom ini ada di tabel 'companies'
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('company_logos', 'public'); // Simpan logo baru
        }

        $company->update($data); // Perbarui data perusahaan

        return redirect()->route('perusahaan.profil')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar lowongan perusahaan.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function lowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $query = Lowongan::where('company_id', $company->id); // Filter lowongan berdasarkan company_id

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('judul', 'like', "%{$searchTerm}%") // Cari berdasarkan judul
                  ->orWhere('deskripsi', 'like', "%{$searchTerm}%"); // Cari berdasarkan deskripsi
        }

        $lowongans = $query->latest()->paginate(10); // Ambil lowongan terbaru dengan paginasi

        return view('perusahaan.lowongan', compact('company', 'lowongans')); // Tampilkan view lowongan
    }

    /**
     * Menampilkan form untuk menambah lowongan baru.
     * @return \Illuminate\View\View
     */
    public function createLowongan()
    {
        return view('perusahaan.tambah_lowongan'); // Tampilkan view tambah lowongan
    }

    /**
     * Menyimpan lowongan baru.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi_lowongan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|in:Full-time,Part-time,Magang,Kontrak',
            'gaji' => 'nullable|numeric|min:0',
            'tanggal_tutup' => 'required|date|after_or_equal:today',
            'kualifikasi' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif,Ditutup',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        Lowongan::create([
            'company_id' => $company->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi_lowongan,
            'lokasi' => $request->lokasi,
            'tipe' => $request->tipe_pekerjaan,
            'gaji_min' => $request->gaji,
            'tanggal_tutup' => $request->tanggal_tutup,
            'kualifikasi' => $request->kualifikasi,
            'tanggung_jawab' => $request->tanggung_jawab,
            'status' => $request->status,
        ]);

        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Menampilkan aktivitas magang.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function aktivitas_magang()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil lowongan IDs yang dimiliki oleh perusahaan ini
        $lowongansCollection = $company->lowongans;
        $lowonganIds = optional($lowongansCollection)->map(function ($lowongan) {
            return $lowongan->id;
        })->toArray() ?? [];

        // Ambil pendaftar yang statusnya 'Diterima' untuk lowongan perusahaan ini
        // dan filter berdasarkan lowonganIds yang dimiliki oleh perusahaan ini.
        $pendaftars = Pendaftar::whereIn('lowongan_id', $lowonganIds)
                                ->where('status_lamaran', 'Diterima')
                                ->with('user')
                                ->get();

        return view('perusahaan.aktivitas_magang', compact('pendaftars', 'company'));
    }
}