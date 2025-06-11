<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Still useful for general password checks, but not used for this form
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password; // Not directly used in this form
use Illuminate\Validation\ValidationException;
use App\Models\Company; // Ensure this is imported

class ProfilePerusahaanController extends Controller
{
    /**
     * Display the perusahaan's profile.
     * Route: perusahaan.profile.perusahaanProfile2
     */
    public function show()
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        // Check if a user is authenticated
        if (!$perusahaan) {
            // Handle case where user is not authenticated, e.g., redirect to login
            return redirect()->route('login'); // Or return an error view
        }

        // Fetch the company associated with the authenticated user
        // Assuming your User model has a hasOne('company') relationship
        $company = $perusahaan->company; // This will return null if no company is linked

        // Return the view, passing both the user and the company data
        return view('perusahaan.Profile.perusahaanProfile', compact('perusahaan', 'company'));
    }

    /**
     * Show the form for editing the perusahaan's profile.
     * Route: perusahaan.profile.edit2
     */
    public function edit()
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        // Fetch the company data for the edit form
        $company = $perusahaan->company;

        // Ensure company data exists before proceeding to edit form
        if (!$company) {
            return redirect()->route('profile.perusahaanProfile2')->with('error', 'Data perusahaan tidak ditemukan.');
        }

        // Return the edit view
        return view('perusahaan.Profile.edit', compact('perusahaan', 'company'));
    }

    /**
     * Update the perusahaan's profile.
     * Route: perusahaan.profile.update2
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        // Ensure the authenticated user has a company profile
        if (!$perusahaan->company) {
            return redirect()->route('perusahaan.profile.perusahaanProfile2')->with('error', 'Profil perusahaan tidak ditemukan.');
        }

        $company = $perusahaan->company; // Get the associated company model

        // Define validation rules for the company fields only
        $rules = [
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20'],
            'email_perusahaan' => ['required', 'string', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'industri' => ['nullable', 'string', 'max:255'],
            'ukuran_perusahaan' => ['nullable', 'string', 'max:255'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2048'], // Matches form name
        ];

        // Validate the request data
        $request->validate($rules);

        // Prepare data for company update
        $companyData = $request->only([
            'nama_perusahaan',
            'telepon',
            'email_perusahaan',
            'website',
            'deskripsi',
            'industri',
            'ukuran_perusahaan',
            'alamat',
            'kota',
            'provinsi',
            'kode_pos',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();
            // Store the logo in 'company_logos' directory in public storage
            $path = $file->storeAs('company_logos', $filename, 'public');
            $companyData['logo_path'] = $path; // Update the logo_path field for the company
        }

        // Update the company data
        $company->update($companyData);

        // Redirect back to the company profile page with a success message
        return redirect()->route('perusahaan.profile.perusahaanProfile2')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
