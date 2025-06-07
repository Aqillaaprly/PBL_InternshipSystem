<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Auth;
use App\Models\Company; // Assuming you have a Company model

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated company's ID
        $companyId = Auth::user()->company->id; // Adjust based on how your company is linked to the user

        // Fetch only recent pendaftar with 'Ditinjau' status for the current company's lowongan
        $recentPendaftars = Pendaftar::whereHas('lowongan', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('status_lamaran', 'Ditinjau') // <--- Add this condition
            ->latest('tanggal_daftar') // Order by latest application date
            ->take(5) // Limit to a few recent ones for the dashboard
            ->get();

        // Fetch the company details for the welcome message
        $company = Auth::user()->company; // Assuming a direct relationship

        return view('perusahaan.dashboard', compact('recentPendaftars', 'company'));
    }
}   