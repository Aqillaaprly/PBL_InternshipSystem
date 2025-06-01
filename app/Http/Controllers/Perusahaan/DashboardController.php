<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lowongan;
use App\Models\PendaftarMagang; // Adjust model name as needed

class DashboardController extends Controller
{



    
    public function index()
    {
        // Get the authenticated company/user ID
        $companyId = Auth::id();
        
        // Count lowongan for this company
        $jumlahLowongan = Lowongan::where('company_id', $companyId)->count();
        
        // Count pendaftar for this company's lowongan
        // This assumes you have a relationship between pendaftar and lowongan
        $jumlahPendaftar = PendaftarMagang::whereHas('lowongan', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->count();
        
        // Alternative if you have a direct company_id in pendaftar table:
        // $jumlahPendaftar = PendaftarMagang::where('company_id', $companyId)->count();
        
        return view('perusahaan.dashboard', compact('jumlahLowongan', 'jumlahPendaftar'));
    }
}

