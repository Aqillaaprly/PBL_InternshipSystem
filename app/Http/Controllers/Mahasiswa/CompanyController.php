<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    public function showProfile(Company $company)
    {
        $company->load(['lowongans' => function($query) {
            $query->where('status', 'Aktif')->orderBy('created_at', 'desc');
        }]);

        return view('mahasiswa.company_profile', compact('company'));
    }
}
