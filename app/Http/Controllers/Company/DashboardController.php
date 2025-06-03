<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Ambil ID semua lowongan milik perusahaan
        $lowonganIds = Lowongan::where('company_id', $company->id)->pluck('id');
        
        // Query pendaftar dengan logika yang sama seperti PendaftarController
        $query = Pendaftar::with(['user', 'lowongan'])->whereIn('lowongan_id', $lowonganIds);
        
        // Implementasi search yang sama
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

        // Filter lowongan yang sama
        if ($request->filled('filter_lowongan_id')) {
            $query->where('lowongan_id', $request->filter_lowongan_id);
        }

        // Data untuk dashboard
        $jumlahTotalPendaftar = Pendaftar::whereIn('lowongan_id', $lowonganIds)->count(); // Total tanpa filter
        $pendaftars = $query->latest('tanggal_daftar');
        $lowonganPerusahaan = Lowongan::where('company_id', $company->id)->orderBy('judul')->get();

        // Data statistik tambahan untuk dashboard
        $jumlahLowonganAktif = Lowongan::where('company_id', $company->id)
                                     ->where('status', 'aktif')
                                     ->count();
        
        $pendaftarBulanIni = Pendaftar::whereIn('lowongan_id', $lowonganIds)
                                    ->whereMonth('tanggal_daftar', now()->month)
                                    ->whereYear('tanggal_daftar', now()->year)
                                    ->count();

        return view('perusahaan.dashboard', compact('company', 'pendaftars'));
    }
}