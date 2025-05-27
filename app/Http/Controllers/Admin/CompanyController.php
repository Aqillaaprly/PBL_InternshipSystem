<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('user')->latest()->paginate(10); // Ambil data perusahaan dengan relasi user
        // Variabel $jumlahPerusahaan untuk dashboard bisa dihitung di sini atau di UserController
        $jumlahPerusahaan = Company::count();
        return view('admin.perusahaan', compact('companies', 'jumlahPerusahaan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Anda mungkin perlu mengirimkan data lain ke view create jika diperlukan
        return view('admin.company.create'); // Buat view ini: resources/views/admin/company/create.blade.php
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
            'website' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Contoh validasi logo
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk membuat user baru untuk perusahaan
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Dapatkan role 'perusahaan'
        $perusahaanRole = Role::where('name', 'perusahaan')->first();
        if (!$perusahaanRole) {
            return redirect()->route('admin.perusahaan.create')->with('error', 'Role "perusahaan" tidak ditemukan.');
        }

        // Buat user baru untuk perusahaan
        $user = User::create([
            'name' => $request->nama_perusahaan, // Menggunakan nama perusahaan sebagai nama user
            'email' => $request->email_perusahaan, // Menggunakan email perusahaan sebagai email user
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $perusahaanRole->id,
        ]);

        $path = null;
        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('logos', 'public'); // Simpan di storage/app/public/logos
        }

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
            'logo_path' => $path,
            'status_kerjasama' => $request->status_kerjasama,
        ]);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company) // Menggunakan Route Model Binding
    {
        return view('admin.company.show', compact('company')); // Buat view ini: resources/views/admin/company/show.blade.php
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company')); // Buat view ini: resources/views/admin/company/edit.blade.php
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
            'website' => 'nullable|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk update user terkait (jika diperlukan)
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $company->user_id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.edit', $company->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except(['logo_path', 'username', 'password', 'password_confirmation']); // Ambil semua kecuali logo, username, password

        if ($request->hasFile('logo_path')) {
            // Hapus logo lama jika ada
            if ($company->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        $company->update($data);

        // Update user terkait jika ada input username atau password
        if ($company->user) {
            $userData = [];
            if ($request->filled('username')) {
                $userData['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            // Update email dan nama user juga jika email_perusahaan atau nama_perusahaan berubah
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
        // Hapus logo dari storage jika ada
        if ($company->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo_path);
        }

        // Hapus user terkait jika ada dan jika diperlukan
        // if ($company->user) {
        //     $company->user->delete();
        // }

        $company->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}