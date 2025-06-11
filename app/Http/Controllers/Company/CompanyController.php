<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // Import Carbon untuk penanganan tanggal

class CompanyController extends Controller
{
    /**
     * Menampilkan profil perusahaan.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show()
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Tampilkan view profil perusahaan dengan data perusahaan.
        return view('perusahaan.show', compact('company'));
    }

    /**
     * Menampilkan form untuk mengedit profil perusahaan.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Tampilkan view edit profil perusahaan dengan data perusahaan.
        return view('perusahaan.edit_profil', compact('company'));
    }

    /**
     * Memperbarui profil perusahaan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Validasi data input dari form.
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'telepon' => 'required|string|max:20',
            'email_perusahaan' => 'required|email|max:255',
            'deskripsi' => 'nullable|string',
            'industri' => 'nullable|string|max:255',
            'ukuran_perusahaan' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input yang sudah ada.
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil semua data request kecuali 'logo' untuk update.
        $data = $request->except('logo');

        // Tangani upload logo jika ada file yang diupload.
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada dan file tersebut benar-benar ada di storage.
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            // Simpan logo baru dan update path-nya.
            $data['logo_path'] = $request->file('logo')->store('company_logos', 'public');
        }

        // Perbarui data perusahaan di database.
        $company->update($data);

        // Arahkan kembali ke halaman profil perusahaan dengan pesan sukses.
        return redirect()->route('perusahaan.profil')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar lowongan perusahaan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function lowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Mulai query untuk lowongan yang dimiliki oleh perusahaan ini.
        $query = Lowongan::where('company_id', $company->id);

        // Tambahkan filter pencarian jika ada input 'search'.
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%") // Cari berdasarkan judul
                  ->orWhere('deskripsi', 'like', "%{$searchTerm}%") // Cari berdasarkan deskripsi
                  ->orWhere('provinsi', 'like', "%{$searchTerm}%") // Cari berdasarkan provinsi
                  ->orWhere('kota', 'like', "%{$searchTerm}%"); // Cari berdasarkan kota
            });
        }

        // Ambil lowongan terbaru dengan paginasi (10 item per halaman).
        $lowongans = $query->latest()->paginate(10);

        // Tampilkan view daftar lowongan dengan data perusahaan dan lowongan.
        return view('perusahaan.lowongan', compact('company', 'lowongans'));
    }

    /**
     * Menampilkan form untuk menambah lowongan baru.
     *
     * @return \Illuminate\View\View
     */
    public function createLowongan()
    {
        // Tampilkan view form tambah lowongan.
        return view('perusahaan.tambah_lowongan');
    }

    /**
     * Menyimpan lowongan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company; // Ambil data perusahaan user

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Validasi data input untuk lowongan baru.
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi_lowongan' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tipe_pekerjaan' => 'required|string|in:Full-time,Part-time,Magang,Kontrak',
            'gaji' => 'nullable|numeric|min:0', // This maps to gaji_min
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'kualifikasi' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif,Ditutup',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input yang sudah ada.
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat lowongan baru di database.
        Lowongan::create([
            'company_id' => $company->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi_lowongan,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'tipe' => $request->tipe_pekerjaan,
            'gaji_min' => $request->gaji, // 'gaji' from form becomes 'gaji_min'
            'gaji_max' => null, // Assuming no gaji_max field in your form
            'tanggal_buka' => $request->tanggal_buka,
            'tanggal_tutup' => $request->tanggal_tutup,
            'kualifikasi' => $request->kualifikasi,
            'tanggung_jawab' => $request->tanggung_jawab,
            'status' => $request->status,
        ]);

        // Arahkan kembali ke daftar lowongan dengan pesan sukses.
        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail lowongan tertentu.
     * Menggunakan Route Model Binding untuk mengambil instance Lowongan.
     *
     * @param  \App\Models\Lowongan  $lowongan
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function showLowongan(Lowongan $lowongan)
    {
        // Pastikan lowongan ini milik perusahaan yang sedang login untuk mencegah akses tidak sah.
        if (Auth::user()->company->id !== $lowongan->company_id) {
            abort(403, 'Anda tidak memiliki akses ke lowongan ini.');
        }

        // Tampilkan view detail lowongan.
        return view('perusahaan.show', compact('lowongan'));
    }

    /**
     * Menampilkan form untuk mengedit lowongan tertentu.
     *
     * @param  \App\Models\Lowongan  $lowongan
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function editLowongan(Lowongan $lowongan)
    {
        // Pastikan lowongan ini milik perusahaan yang sedang login untuk mencegah akses tidak sah.
        if (Auth::user()->company->id !== $lowongan->company_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit lowongan ini.');
        }

        // Tampilkan view edit lowongan dengan data lowongan.
        return view('perusahaan.edit', compact('lowongan'));
    }

    /**
     * Memperbarui lowongan tertentu di penyimpanan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lowongan  $lowongan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLowongan(Request $request, Lowongan $lowongan)
    {
        // Pastikan lowongan ini milik perusahaan yang sedang login untuk mencegah akses tidak sah.
        if (Auth::user()->company->id !== $lowongan->company_id) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui lowongan ini.');
        }

        // Validasi data input untuk pembaruan lowongan.
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi_lowongan' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'tipe_pekerjaan' => 'required|string|in:Full-time,Part-time,Magang,Kontrak',
            'gaji_min' => 'nullable|numeric|min:0', // Changed from 'gaji' to 'gaji_min'
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'kualifikasi' => 'nullable|string',
            'tanggung_jawab' => 'nullable|string',
            'status' => 'required|in:Aktif,Nonaktif,Ditutup',
        ]);

        // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error dan input yang sudah ada.
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Perbarui data lowongan di database.
        $lowongan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi_lowongan,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'kode_pos' => $request->kode_pos,
            'tipe' => $request->tipe_pekerjaan,
            'gaji_min' => $request->gaji_min, // 'gaji_min' from form
            'gaji_max' => null, // Assuming no gaji_max field in your form
            'tanggal_buka' => $request->tanggal_buka,
            'tanggal_tutup' => $request->tanggal_tutup,
            'kualifikasi' => $request->kualifikasi,
            'tanggung_jawab' => $request->tanggung_jawab,
            'status' => $request->status,
        ]);

        // Arahkan kembali ke halaman manajemen lowongan dengan pesan sukses.
        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Menghapus lowongan tertentu dari penyimpanan.
     *
     * @param  \App\Models\Lowongan  $lowongan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyLowongan(Lowongan $lowongan)
    {
        // Pastikan lowongan ini milik perusahaan yang sedang login untuk mencegah akses tidak sah.
        if (Auth::user()->company->id !== $lowongan->company_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus lowongan ini.');
        }

        // Hapus lowongan dari database.
        $lowongan->delete();

        // Arahkan kembali ke daftar lowongan dengan pesan sukses.
        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil dihapus.');
    }

    /**
     * Menampilkan aktivitas magang (pendaftar yang diterima).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function aktivitas_magang()
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            // Jika profil perusahaan tidak ditemukan, arahkan ke login dengan pesan error.
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil ID lowongan yang dimiliki oleh perusahaan ini.
        // Gunakan optional() untuk menghindari error jika lowongansCollection adalah null.
        $lowonganIds = optional($company->lowongans)->map(function ($lowongan) {
            return $lowongan->id;
        })->toArray() ?? [];

        // Ambil pendaftar yang statusnya 'Diterima' untuk lowongan perusahaan ini
        // dan eager load relasi 'user' untuk mendapatkan detail pengguna.
        $pendaftars = Pendaftar::whereIn('lowongan_id', $lowonganIds)
            ->where('status_lamaran', 'Diterima')
            ->with('user')
            ->get();

        // Tampilkan view aktivitas magang dengan data pendaftar dan perusahaan.
        return view('perusahaan.aktivitas_magang', compact('pendaftars', 'company'));
    }
}