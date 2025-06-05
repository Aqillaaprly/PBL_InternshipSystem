<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Company; // Pastikan ini di-import
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Menampilkan dasbor perusahaan.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil statistik dasar untuk dasbor perusahaan
        $jumlahLowonganAktif = Lowongan::where('company_id', $company->id)
                                ->where('status', 'Aktif')
                                ->count();

        $jumlahLowonganTidakAktif = Lowongan::where('company_id', $company->id)
                                ->where('status', 'Non-Aktif')
                                ->count();

        // Mengakses relasi lowongans (plural) dari Company model.
        // Ini akan mengembalikan Koleksi (bahkan jika kosong), jadi pluck() tidak akan pada null.
        $lowonganIds = $company->lowongans->pluck('id')->toArray();

        // Jika tidak ada lowongan yang ditemukan untuk perusahaan ini,
        // maka tidak akan ada pendaftar yang diambil.
        if (empty($lowonganIds)) {
            $pendaftars = Pendaftar::whereRaw('1 = 0')->paginate(5);
            $jumlahTotalPendaftar = 0;
        } else {
            $queryPendaftars = Pendaftar::with(['mahasiswa.user', 'lowongan.company']) // Eager load company for lowongan
                                       ->whereIn('lowongan_id', $lowonganIds);

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $queryPendaftars->where(function ($q) use ($searchTerm) {
                    $q->whereHas('mahasiswa.user', function ($uq) use ($searchTerm) {
                        $uq->where('name', 'like', "%{$searchTerm}%")
                           ->orWhere('username', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('lowongan', function ($lq) use ($searchTerm) {
                        $lq->where('judul', 'like', "%{$searchTerm}%");
                    });
                });
            }
            $pendaftars = $queryPendaftars->latest('tanggal_daftar')->paginate(5)->withQueryString();

            $jumlahTotalPendaftar = Pendaftar::whereIn('lowongan_id', $lowonganIds)->count();
        }

        $pendaftarBulanIni = Pendaftar::whereIn('lowongan_id', $lowonganIds)
                                    ->whereMonth('tanggal_daftar', now()->month)
                                    ->whereYear('tanggal_daftar', now()->year)
                                    ->count();

        return view('perusahaan.dashboard', compact(
            'company',
            'jumlahLowonganAktif',
            'jumlahTotalPendaftar',
            'jumlahLowonganTidakAktif',
            'pendaftars'
        ));
    }

    // Metode untuk menampilkan daftar lowongan perusahaan
    public function lowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $query = Lowongan::where('company_id', $company->id);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('judul', 'like', "%{$searchTerm}%")
                  ->orWhere('deskripsi', 'like', "%{$searchTerm}%");
        }

        $lowongans = $query->latest()->paginate(10); // Menggunakan relasi lowongans (plural)

        return view('perusahaan.lowongan', compact('company', 'lowongans'));
    }

    // Metode lain dalam CompanyController...
    public function show()
    {
        $user = Auth::user();
        $company = $user->company; // Assuming company is retrieved here too
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }
        return view('perusahaan.profil', compact('company')); // Adjust view name if needed
    }

    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }
        return view('perusahaan.edit_profil', compact('company')); // Adjust view name if needed
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validatedData = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'email_perusahaan' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'industri' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo_perusahaan')) {
            // Delete old logo if exists
            if ($company->logo_perusahaan && Storage::disk('public')->exists($company->logo_perusahaan)) {
                Storage::disk('public')->delete($company->logo_perusahaan);
            }
            $validatedData['logo_perusahaan'] = $request->file('logo_perusahaan')->store('logos', 'public');
        }

        $company->update($validatedData);

        return redirect()->route('perusahaan.profil')->with('success', 'Profil perusahaan berhasil diperbarui.'); // Adjust route name if needed
    }

    public function createLowongan()
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }
        return view('perusahaan.tambah_lowongan', compact('company')); // Adjust view name if needed
    }

    public function storeLowongan(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'tipe_pekerjaan' => 'required|string|in:Full-time,Part-time,Magang,Kontrak',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'tanggal_tutup' => 'required|date|after_or_equal:today',
            'status' => 'required|string|in:Aktif,Non-Aktif',
        ]);

        $company->lowongans()->create($validatedData); // Create lowongan associated with the company

        return redirect()->route('perusahaan.lowongan')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function aktivitas_magang()
    {
        $user = Auth::user();
        $company = $user->company;
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil lowongan IDs yang dimiliki oleh perusahaan ini
        $lowonganIds = $company->lowongans->pluck('id');

        // Ambil bimbingan magang (aktivitas) untuk mahasiswa yang terdaftar di lowongan perusahaan ini
        $aktivitasMagang = \App\Models\BimbinganMagang::whereHas('mahasiswa.pendaftars', function ($query) use ($lowonganIds) {
            $query->whereIn('lowongan_id', $lowonganIds);
        })
        ->with(['mahasiswa.user', 'pembimbing'])
        ->latest('tanggal')
        ->paginate(10);

        return view('perusahaan.aktivitas_magang', compact('company', 'aktivitasMagang'));
    }
}