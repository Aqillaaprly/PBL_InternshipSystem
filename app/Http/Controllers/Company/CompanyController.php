<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // This line was added to resolve the 'Undefined type Storage' error.
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Menampilkan dasbor perusahaan.
     */
    public function dashboard(Request $request) // Add Request $request to get search and filter params
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

        // Fetch pendaftar data with search and pagination for the table
        $lowonganIds = Lowongan::where('company_id', $company->id)->pluck('id');
        $queryPendaftars = Pendaftar::with(['user', 'lowongan.company']) // Eager load company for lowongan
                                   ->whereIn('lowongan_id', $lowonganIds);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $queryPendaftars->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($uq) use ($searchTerm) {
                    $uq->where('name', 'like', "%{$searchTerm}%")
                       ->orWhere('username', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('lowongan', function ($lq) use ($searchTerm) {
                    $lq->where('judul', 'like', "%{$searchTerm}%");
                });
            });
        }
        $pendaftars = $queryPendaftars->latest('tanggal_daftar')->paginate(5)->withQueryString(); // Paginate and pass search query

        // Total pendaftar (without pagination for the total count display)
        $jumlahTotalPendaftar = Pendaftar::whereIn('lowongan_id', $lowonganIds)->count();

        return view('perusahaan.dashboard', compact(
            'company',
            'jumlahLowonganAktif',
            'jumlahTotalPendaftar',
            'jumlahLowonganTidakAktif',
            'pendaftars' // Pass the paginated pendaftar data to the view
        ));
    }

    /**
     * Menampilkan profil detail perusahaan.
     */
    public function show()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        return view('perusahaan.detail', compact('company'));
    }

    /**
     * Menampilkan form edit profil perusahaan.
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        return view('perusahaan.edit', compact('company'));
    }

    /**
     * Update profil perusahaan.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
            'email_perusahaan' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('company_logos', 'public');
            $company->logo_path = $logoPath;
        }

        // Update company data
        $company->update([
            'nama_perusahaan' => $request->nama_perusahaan,
            'email_perusahaan' => $request->email_perusahaan,
            'telepon' => $request->telepon,
            'website' => $request->website,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'deskripsi' => $request->deskripsi,
        ]);

        if (isset($logoPath)) {
            $company->logo_path = $logoPath;
            $company->save();
        }

        return redirect()->route('perusahaan.profil')->with('success', 'Profil perusahaan berhasil diperbarui.');
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
                             ->paginate(10);

        return view('perusahaan.lowongan', compact('company', 'lowongans'));
    }

    /**
     * Menampilkan form untuk menambah lowongan baru.
     */
    public function createLowongan()
    {
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

        return view('perusahaan.pendaftar', compact('company', 'pendaftars', 'lowonganPerusahaan'));
    }

    /**
     * Memperbarui status pendaftar.
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
            // Uncomment if you have catatan_perusahaan field
            // $pendaftar->catatan_perusahaan = $request->catatan_perusahaan;
        }
        $pendaftar->save();

        return redirect()->route('perusahaan.pendaftar')->with('success', 'Status pendaftar berhasil diperbarui.');
    }
}
