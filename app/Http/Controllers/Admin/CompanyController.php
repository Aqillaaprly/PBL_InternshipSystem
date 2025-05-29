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
        // $jumlahPerusahaan = Company::count(); // Ini mungkin lebih cocok di DashboardController
        // return view('admin.perusahaan', compact('companies', 'jumlahPerusahaan'));
        return view('admin.Company.perusahaan', compact('companies')); // Corrected view path
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pastikan view ini ada: resources/views/admin/Company/create.blade.php
        // Sesuai permintaan sebelumnya, ini adalah nama view yang ada
        return view('admin.Company.create');
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
            'logo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk User terkait Perusahaan
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed', // 'confirmed' akan mencari field 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $logoPath = null;
        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            $logoPath = $request->file('logo_path')->store('logos', 'public');
        } else {
            // Seharusnya tidak terjadi jika validasi 'required' bekerja
            return redirect()->route('admin.perusahaan.create')
                             ->with('error', 'Logo perusahaan wajib diunggah dan valid.')
                             ->withInput();
        }

        $perusahaanRole = Role::where('name', 'perusahaan')->first();
        if (!$perusahaanRole) {
            Log::error("Role 'perusahaan' tidak ditemukan saat membuat perusahaan baru via admin.");
            return redirect()->route('admin.perusahaan.create')
                             ->with('error', 'Kesalahan konfigurasi: Role perusahaan tidak ditemukan.')
                             ->withInput();
        }

        // Buat User untuk perusahaan
        $user = User::create([
            'name' => $request->nama_perusahaan, // Atau nama kontak perusahaan
            'email' => $request->email_perusahaan, // Atau email login khusus untuk user perusahaan
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $perusahaanRole->id,
        ]);

        Company::create([
            'user_id' => $user->id, // Kaitkan dengan user yang baru dibuat
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'telepon' => $request->telepon,
            'email_perusahaan' => $request->email_perusahaan, // Email resmi perusahaan
            'website' => $request->website,
            'deskripsi' => $request->deskripsi,
            'logo_path' => $logoPath,
            'status_kerjasama' => $request->status_kerjasama,
        ]);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        // Pastikan view ini ada: resources/views/admin/Company/show.blade.php (atau sesuaikan)
        // Jika tidak ada view show khusus, Anda mungkin mengarahkannya ke edit atau index.
        // Untuk saat ini, kita asumsikan view 'show' ada atau akan dibuat.
        return view('admin.Company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        // Pastikan view ini ada: resources/views/admin/Company/edit.blade.php
        return view('admin.Company.edit', compact('company'));
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
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo opsional saat update
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk User terkait Perusahaan (jika diupdate)
            // Pastikan user_id ada di $company sebelum mencoba mengakses $company->user->id
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . ($company->user_id ? $company->user->id : 'NULL') . ',id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.edit', $company->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except(['_token', '_method', 'logo_path', 'username', 'password', 'password_confirmation']);

        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            // Hapus logo lama jika ada
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }
        // Jika tidak ada logo baru yang diunggah, $data['logo_path'] tidak akan diset,
        // sehingga $company->logo_path yang ada akan dipertahankan.

        $company->update($data);

        // Update detail User terkait jika ada dan jika data user dikirim
        if ($company->user) {
            $userData = [];
            if ($request->filled('username')) {
                $userData['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            // Selalu update nama dan email user jika nama/email perusahaan berubah
            // Ini asumsi bahwa 'name' di User adalah nama perusahaan dan 'email' adalah email login perusahaan.
            // Sesuaikan jika field di User berbeda.
            $userData['name'] = $request->nama_perusahaan;
            // $userData['email'] = $request->email_perusahaan; // Hati-hati jika email ini untuk login user

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
        // Hapus logo dari storage jika ada
        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        // Opsional: Hapus User terkait jika logika bisnis mengharuskannya
        // Hati-hati dengan ini, user mungkin terkait dengan data lain.
        // if ($company->user) {
        //     $company->user->delete();
        // }

        $company->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}