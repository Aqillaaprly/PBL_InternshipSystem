<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Display the company profile with related data
     *
     * @param Company $company
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse
     */
    public function showProfile(Company $company)
    {
        try {
            // Eager load relationships with optimized queries
            $company->load([
                'user' => function($query) {
                    $query->select('id', 'name', 'email');
                },
                'lowongans' => function($query) {
                    $query->where('status', 'Aktif')
                        ->orderBy('created_at', 'desc')
                        ->select(['id', 'company_id', 'judul', 'lokasi', 'status', 'created_at']);
                }
            ]);

            // Prepare the view data
            $viewData = [
                'company' => $company,
                'activeLowonganCount' => $company->lowongans->count(),
            ];

            // Handle AJAX requests
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'html' => view('mahasiswa.company_profile', $viewData)->render()
                ]);
            }

            // Return full view for direct access
            return view('mahasiswa.company_profile', $viewData);

        } catch (\Exception $e) {
            Log::error('Failed to load company profile', [
                'company_id' => $company->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Gagal memuat profil perusahaan. Silakan coba lagi.';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $errorMessage
                ], 500);
            }

            return back()->with('error', $errorMessage);
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

    /**
     * Helper method to get lowongan status badge class
     *
     * @param string $status
     * @return string
     */
    protected function getLowonganStatusBadgeClass($status)
    {
        return $status == 'Aktif'
            ? 'bg-green-100 text-green-700'
            : 'bg-gray-100 text-gray-700';
    }
}
