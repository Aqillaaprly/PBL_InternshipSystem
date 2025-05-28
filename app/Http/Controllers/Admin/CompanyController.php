<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Added for logging errors

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(10);
        $jumlahPerusahaan = Company::count();
        return view('admin.perusahaan', compact('companies', 'jumlahPerusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.company.create'); // Ensure this view exists
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255|unique:companies,nama_perusahaan',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20|unique:companies,telepon',
            'email_perusahaan' => 'required|string|email|max:255|unique:companies,email_perusahaan',
            'website' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo is required
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Handle file upload for logo
        $logoPath = null;
        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            $logoPath = $request->file('logo_path')->store('logos', 'public');
        } else {
            // This condition should ideally not be met if 'required' validation works
            // and form has enctype="multipart/form-data"
            return redirect()->route('admin.perusahaan.create')
                             ->with('error', 'Logo perusahaan wajib diunggah dan harus merupakan file gambar yang valid.')
                             ->withInput();
        }

        $perusahaanRole = Role::where('name', 'perusahaan')->first();
        if (!$perusahaanRole) {
            Log::error("Role 'perusahaan' tidak ditemukan saat membuat perusahaan baru.");
            return redirect()->route('admin.perusahaan.create')
                             ->with('error', 'Terjadi kesalahan konfigurasi sistem. Silakan hubungi administrator.')
                             ->withInput();
        }

        $user = User::create([
            'name' => $request->nama_perusahaan,
            'email' => $request->email_perusahaan,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $perusahaanRole->id,
        ]);

        Company::create([
            'user_id' => $user->id,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'telepon' => $request->telepon,
            'email_perusahaan' => $request->email_perusahaan,
            'website' => $request->website,
            'deskripsi' => $request->deskripsi,
            'logo_path' => $logoPath, // Use the stored path
            'status_kerjasama' => $request->status_kerjasama,
        ]);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('admin.company.show', compact('company')); // Ensure this view exists
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company')); // Ensure this view exists
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255|unique:companies,nama_perusahaan,' . $company->id,
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20|unique:companies,telepon,' . $company->id,
            'email_perusahaan' => 'required|string|email|max:255|unique:companies,email_perusahaan,' . $company->id,
            'website' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo is optional on update
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . ($company->user_id ?? 'NULL') . ',id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.edit', $company->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except(['_token', '_method', 'logo_path', 'username', 'password', 'password_confirmation']);

        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            // Delete old logo if it exists
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }
        // If no new logo is uploaded, $data['logo_path'] will not be set,
        // so the existing $company->logo_path will be preserved during the update,
        // which is correct because the database field is NOT NULL.

        $company->update($data);

        // Update user related details if company has a user
        if ($company->user) {
            $userData = [];
            if ($request->filled('username')) {
                $userData['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            // Always update name and email of user if company name/email changes
            $userData['name'] = $request->nama_perusahaan;
            $userData['email'] = $request->email_perusahaan;

            if (!empty($userData)) {
                $company->user->update($userData);
            }
        }

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Delete logo from storage if it exists
        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        // Optionally delete the associated user if the business logic requires it
        // Be cautious with this, as the user might be linked elsewhere or have other roles.
        // if ($company->user) {
        //     $company->user->delete();
        // }

        $company->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}