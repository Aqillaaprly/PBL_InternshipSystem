<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarController extends Controller
{
    public function pendaftar(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $lowonganIds = Lowongan::where('company_id', $company->id)->pluck('id');
        $query = Pendaftar::with(['user', 'lowongan'])->whereIn('lowongan_id', $lowonganIds);

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
        $jumlahTotalPendaftar = $query->count();
        $lowonganPerusahaan = Lowongan::where('company_id', $company->id)->orderBy('judul')->get();

        return view('perusahaan.pendaftar', compact('company', 'pendaftars', 'lowonganPerusahaan', 'jumlahTotalPendaftar'));
    }

    public function show(Pendaftar $pendaftar)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('login')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        // Verify that this pendaftar belongs to a job posting from this company
        if ($pendaftar->lowongan->company_id !== $company->id) {
            return redirect()->route('perusahaan.pendaftar')
                ->with('error', 'Anda tidak memiliki akses untuk melihat pendaftar ini.');
        }

        // Load all necessary relationships
        $pendaftar->load([
            'user.profile', // Assuming you have a profile relation
            'lowongan.company'
        ]);

        return view('perusahaan.pendaftar.show', compact('pendaftar', 'company'));
    }
}