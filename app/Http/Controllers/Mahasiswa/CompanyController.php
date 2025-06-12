<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function perusahaan(Request $request)
    {
        $query = Company::query()->withCount('lowongans');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%")
                    ->orWhere('email_perusahaan', 'like', "%$search%")
                    ->orWhere('kota', 'like', "%$search%")
                    ->orWhere('provinsi', 'like', "%$search%");
            });
        }

        $companies = $query->paginate(10);

        return view('mahasiswa.perusahaan', compact('companies'));
    }

    /**
     * Display the company profile page
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function showProfile($id)
    {
        try {
            $company = Company::with([
                'user:id,name,email',
                'lowongans' => function($query) {
                    $query->where('status', 'Aktif')
                        ->orderBy('created_at', 'desc')
                        ->select('id', 'company_id', 'judul', 'status', 'created_at');
                }
            ])->findOrFail($id);

            return view('mahasiswa.company_profile', [
                'company' => $company,
                'activeLowonganCount' => $company->lowongans->count()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Company not found', ['company_id' => $id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Perusahaan tidak ditemukan.');
        }
    }

    /**
     * Helper method to get company status badge class
     *
     * @param string $status
     * @return string
     */
    protected function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'Aktif':
                return 'bg-green-100 text-green-700';
            case 'Non-Aktif':
                return 'bg-red-100 text-red-700';
            default:
                return 'bg-yellow-100 text-yellow-700';
        }
    }
}
