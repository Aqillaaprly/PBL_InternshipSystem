<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Company;
use App\Models\KualifikasiLowongan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CompanyController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $jumlahLowonganAktif = Lowongan::where('company_id', $company->id)
            ->where('status', 'Aktif')
            ->count();

        $jumlahTotalPendaftar = Pendaftar::whereHas('lowongan', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->count();

        return view('perusahaan.dashboard', compact('company', 'jumlahLowonganAktif', 'jumlahTotalPendaftar'));
    }

    public function lowongan()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $lowongans = Lowongan::where('company_id', $company->id)
            ->latest()
            ->paginate(10);

        return view('perusahaan.lowongan', compact('company', 'lowongans'));
    }

    public function createLowongan()
    {
        return view('perusahaan.tambah_lowongan');
    }

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

        $lowongan = Lowongan::create([
            'company_id' => $company->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'lokasi' => $request->lokasi,
            'gaji_min' => $request->gaji_min,
            'gaji_max' => $request->gaji_max,
            'tanggal_buka' => Carbon::now()->toDateString(),
            'tanggal_tutup' => $request->tanggal_tutup,
            'status' => 'Aktif',
        ]);

        // Simpan kualifikasi satu per satu
        $kualifikasis = preg_split('/\r\n|\r|\n/', $request->kualifikasi);
        foreach ($kualifikasis as $kualifikasi) {
            if (trim($kualifikasi) !== '') {
                KualifikasiLowongan::create([
                    'lowongan_id' => $lowongan->id,
                    'keterangan' => $kualifikasi,
                ]);
            }
        }

        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

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
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($subQ) use ($searchTerm) {
                    $subQ->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('username', 'like', "%{$searchTerm}%");
                })->orWhereHas('lowongan', function ($subQ) use ($searchTerm) {
                    $subQ->where('judul', 'like', "%{$searchTerm}%");
                });
            });
        }

        if ($request->filled('filter_lowongan_id')) {
            $query->where('lowongan_id', $request->filter_lowongan_id);
        }

        $pendaftars = $query->latest('tanggal_daftar')->paginate(10);
        $lowonganPerusahaan = Lowongan::where('company_id', $company->id)->orderBy('judul')->get();

        return view('perusahaan.pendaftar', compact('company', 'pendaftars', 'lowonganPerusahaan'));
    }

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
        // Uncomment this line when you want to store the note
        // $pendaftar->catatan_perusahaan = $request->catatan_perusahaan;
        $pendaftar->save();

        return redirect()->route('perusahaan.pendaftar')->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
