<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BimbinganMagang;
use App\Models\Company;
use App\Models\Lowongan;
use App\Models\Pendaftar;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $jumlahPerusahaan = Company::count();
        $jumlahLowongan = Lowongan::where('status', 'Aktif')->count();
        $jumlahPendaftar = Pendaftar::where('user_id', Auth::id())->count();

        // Get recommended lowongan if exists
        $recommendedLowongan = null;
        if (session('recommended_lowongan_id')) {
            $recommendedLowongan = Lowongan::with('company')
                ->where('id', session('recommended_lowongan_id'))
                ->where('status', 'Aktif')
                ->whereDate('tanggal_tutup', '>=', now())
                ->first();
        }

        return view('mahasiswa.dashboard', compact(
            'jumlahPerusahaan',
            'jumlahLowongan',
            'jumlahPendaftar',
            'recommendedLowongan'
        ));
    }


    public function lihatPembimbing()
    {
        $userMahasiswa = Auth::user();

        $bimbinganAktif = BimbinganMagang::with(['pembimbing.user', 'company'])
            ->where('mahasiswa_user_id', $userMahasiswa->id)
            ->where('status_bimbingan', 'Aktif')
            ->first();

        return view('mahasiswa.dosen_pembimbing', compact('bimbinganAktif'));
    }

    public function laporan(Request $request)
    {
        $userId = Auth::id();

        $query = BimbinganMagang::where('mahasiswa_user_id', $userId)
            ->with(['pembimbing.user', 'company', 'lowongan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('company', function($q) use ($search) {
                    $q->where('nama_perusahaan', 'like', "%$search%");
                })->orWhereHas('pembimbing.user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }

        $bimbingans = $query->paginate(10);

        return view('mahasiswa.laporan', compact('bimbingans'));
    }

    // Update perusahaan method to filter by recommendation:
    public function perusahaan(Request $request)
    {
        $query = Company::with('lowongans');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%")
                    ->orWhere('email_perusahaan', 'like', "%$search%")
                    ->orWhere('kota', 'like', "%$search%");
            });
        }

        // Filter by recommendation if exists
        if (session('recommended_job')) {
            $query->whereHas('lowongans', function($q) {
                $q->where('judul', 'like', '%' . session('recommended_job') . '%');
            });
        }

        $companies = $query->orderBy('nama_perusahaan')->paginate(10);

        return view('mahasiswa.perusahaan', compact('companies'));
    }

    public function job()
    {
        // Get companies with their active job listings
        $companies = Company::with(['lowongans' => function($query) {
            $query->where('status', 'Aktif')
                ->whereDate('tanggal_tutup', '>=', now())
                ->orderBy('tanggal_tutup', 'asc');
        }])
            ->whereHas('lowongans', function($q) {
                $q->where('status', 'Aktif')
                    ->whereDate('tanggal_tutup', '>=', now());
            })
            ->where('status_kerjasama', 'Aktif')
            ->orderBy('nama_perusahaan')
            ->paginate(10);

        return view('mahasiswa.job', compact('companies'));
    }
}
