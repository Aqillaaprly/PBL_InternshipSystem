<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard perusahaan.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (! $company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $totalPendaftar = Pendaftar::whereHas('lowongan', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->count();

        $lowonganAktifCount = Lowongan::where('company_id', $company->id)->where('status', 'aktif')->count();

        $recentPendaftars = Pendaftar::whereHas('lowongan', function ($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('status_lamaran', 'Diterima')
            ->with(['user', 'lowongan'])
            ->latest()
            ->take(5)
            ->get();

        return view('perusahaan.dashboard', compact('company', 'totalPendaftar', 'lowonganAktifCount', 'recentPendaftars'));
    }
}
