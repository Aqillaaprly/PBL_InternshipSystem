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
use Illuminate\Support\Facades\Log; // Ditambahkan untuk logging error

class CompanyController extends Controller
{
    /**
     * Menampilkan daftar semua perusahaan.
     */
    public function index(Request $request) // Tambahkan Request untuk pencarian
    {
        $query = Company::with('user')->latest();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_perusahaan', 'like', "%{$searchTerm}%")
                  ->orWhere('email_perusahaan', 'like', "%{$searchTerm}%")
                  ->orWhere('kota', 'like', "%{$searchTerm}%")
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('username', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $companies = $query->paginate(10)->withQueryString(); // withQueryString untuk menjaga parameter search saat paginasi
        
        // Path view sudah benar berdasarkan struktur file yang ada
        return view('admin.Company.perusahaan', compact('companies'));
    }

    /**
     * Menampilkan formulir untuk membuat perusahaan baru.
     */
    public function create()
    {
        // Path view sudah benar berdasarkan struktur file yang ada
        return view('admin.Company.create');
    }

    /**
     * Menyimpan perusahaan baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255|unique:companies,nama_perusahaan',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20|unique:companies,telepon,NULL,id,deleted_at,NULL', // Memastikan unik jika tidak soft delete
            'email_perusahaan' => 'required|string|email|max:255|unique:companies,email_perusahaan,NULL,id,deleted_at,NULL',
            'website' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk User terkait Perusahaan
            'username' => 'required|string|max:255|unique:users,username,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
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
            'name' => $request->nama_perusahaan, // Nama user bisa diambil dari nama perusahaan
            'email' => $request->email_perusahaan, // Email login bisa sama dengan email perusahaan atau berbeda
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $perusahaanRole->id,
            'email_verified_at' => now(), // Langsung verifikasi email saat dibuat oleh admin
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
            'logo_path' => $logoPath,
            'status_kerjasama' => $request->status_kerjasama,
        ]);

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail spesifik perusahaan.
     */
    public function show(Company $company)
    {
        // Pastikan view ini ada: resources/views/admin/Company/show.blade.php
        // Jika tidak ada, Anda bisa membuat view sederhana atau mengarahkan ke edit.
        // Untuk saat ini, diasumsikan view 'show' akan dibuat atau sudah ada.
        $company->load('user'); // Eager load user terkait
        return view('admin.Company.show', compact('company'));
    }

    /**
     * Menampilkan formulir untuk mengedit perusahaan.
     */
    public function edit(Company $company)
    {
        // Path view sudah benar berdasarkan struktur file yang ada
        $company->load('user'); // Eager load user terkait agar bisa ditampilkan di form edit
        return view('admin.Company.edit', compact('company'));
    }

    /**
     * Memperbarui data perusahaan di database.
     */
    public function update(Request $request, Company $company)
    {
        $userIdToIgnore = $company->user ? $company->user->id : 'NULL';

        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255|unique:companies,nama_perusahaan,' . $company->id,
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20|unique:companies,telepon,' . $company->id . ',id,deleted_at,NULL',
            'email_perusahaan' => 'required|string|email|max:255|unique:companies,email_perusahaan,' . $company->id . ',id,deleted_at,NULL',
            'website' => 'required|url|max:255',
            'deskripsi' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Validasi untuk User terkait Perusahaan
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $userIdToIgnore . ',id,deleted_at,NULL',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.edit', $company->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except(['_token', '_method', 'logo_path', 'username', 'password', 'password_confirmation']);

        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        $company->update($data);

        // Update detail User terkait
        if ($company->user) {
            $userData = [];
            if ($request->filled('username')) {
                $userData['username'] = $request->username;
            }
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            // Update nama dan email user jika nama/email perusahaan berubah
            // (sesuaikan jika field di User berbeda atau jika email login tidak boleh sama dengan email perusahaan)
            $userData['name'] = $request->nama_perusahaan;
            // $userData['email'] = $request->email_perusahaan; // Hati-hati, email user mungkin untuk login

            if (!empty($userData)) {
                $company->user->update($userData);
            }
        }

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Menghapus perusahaan dari database.
     */
    public function destroy(Company $company)
    {
        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        // Opsional: Hapus User terkait jika itu adalah kebijakan Anda
        // if ($company->user) {
        //     $company->user->delete(); // Ini akan menghapus user yang login untuk perusahaan tersebut
        // }

        $company->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil dihapus.');
    }
}
