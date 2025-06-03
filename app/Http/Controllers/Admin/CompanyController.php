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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /**
     * Menampilkan daftar semua perusahaan.
     */
    public function index(Request $request)
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

        $companies = $query->paginate(10)->withQueryString();
        return view('admin.Company.perusahaan', compact('companies'));
    }

    /**
     * Menampilkan formulir untuk membuat perusahaan baru.
     */
    public function create()
    {
        return view('admin.Company.create');
    }

    /**
     * Menyimpan perusahaan baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255|unique:companies,nama_perusahaan',
            'email_perusahaan' => 'required|string|email|max:255|unique:companies,email_perusahaan',
            'website' => 'required|url|max:255',
            'logo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'telepon' => 'nullable|string|max:20|unique:companies,telepon',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
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

        $user = User::create([
            'name' => $request->nama_perusahaan,
            'email' => $request->email_perusahaan,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $perusahaanRole->id,
            'email_verified_at' => now(),
        ]);

        Company::create(array_merge(
            $request->only(['nama_perusahaan', 'alamat', 'kota', 'provinsi', 'kode_pos', 'telepon', 'email_perusahaan', 'website', 'deskripsi', 'status_kerjasama']),
            ['user_id' => $user->id, 'logo_path' => $logoPath]
        ));

        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan beserta akun loginnya.');
    }

    // Menggunakan Route Model Binding (Company $company)
   public function show($companyId) // Ubah parameter menjadi $companyId (bukan Company $company)
{
    Log::info("Attempting to find Company with ID: " . $companyId);

    // Coba ambil data secara manual menggunakan Eloquent
    $company = Company::find($companyId); // Gunakan find() bukan findOrFail() untuk tes awal 

    if (!$company) {
        // Jika tidak ditemukan, ini akan menghasilkan error 404 standar
        // atau Anda bisa menangani secara custom jika dd() di atas menampilkan null
        abort(404, "Perusahaan dengan ID {$companyId} tidak ditemukan via find().");
    }

    $company->load('user');
    return view('admin.Company.show', compact('company'));
}

    // Menggunakan Route Model Binding (Company $company)/ Ubah parameter menjadi $companyId
    /**
     * Menampilkan detail spesifik perusahaan.
     */

    /**
     * Menampilkan formulir untuk mengedit perusahaan.
     */
    public function edit($companyId)
    {
        Log::info("AdminCompanyController@edit: Mencoba mengambil perusahaan untuk diedit dengan ID: " . $companyId);
        $company = Company::find($companyId);

        if (!$company) {
            Log::error("AdminCompanyController@edit: Perusahaan dengan ID {$companyId} tidak ditemukan untuk diedit.");
            abort(404, "Perusahaan dengan ID {$companyId} tidak ditemukan untuk proses edit.");
        }

        $company->load('user');
        return view('admin.Company.edit', compact('company'));
    }

    /**
     * Memperbarui data perusahaan di database.
     */
    public function update(Request $request, $companyId) // Menerima $companyId
    {
        $company = Company::findOrFail($companyId); // Mengambil company berdasarkan ID

        $userIdToIgnore = $company->user ? $company->user->id : 0;
        // $companyIdToIgnore variabel tidak lagi diperlukan karena kita menggunakan $company->id

        $rules = [
            'nama_perusahaan' => ['required', 'string', 'max:255', Rule::unique('companies')->ignore($company->id)],
            'email_perusahaan' => ['required', 'string', 'email', 'max:255', Rule::unique('companies', 'email_perusahaan')->ignore($company->id)],
            'website' => 'required|url|max:255',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            'telepon' => ['nullable', 'string', 'max:20', Rule::unique('companies', 'telepon')->ignore($company->id)],
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'deskripsi' => 'nullable|string',
        ];

        if ($company->user) {
            $rules['username'] = ['sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($userIdToIgnore)];
            $rules['user_email_login'] = ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userIdToIgnore)];
            $rules['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $rules['new_username'] = ['required_with:new_user_email,new_password', 'nullable', 'string', 'max:255', Rule::unique('users', 'username')];
            $rules['new_user_email'] = ['required_with:new_username,new_password', 'nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')];
            $rules['new_password'] = ['required_with:new_username,new_user_email', 'nullable', 'string', 'min:6', 'confirmed'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.perusahaan.edit', $company->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $companyData = $request->only([
            'nama_perusahaan', 'alamat', 'kota', 'provinsi', 'kode_pos',
            'telepon', 'email_perusahaan', 'website', 'deskripsi', 'status_kerjasama'
        ]);

        if ($request->hasFile('logo_path') && $request->file('logo_path')->isValid()) {
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $companyData['logo_path'] = $request->file('logo_path')->store('logos', 'public');
        }

        $company->update($companyData);

        if ($company->user) {
            $userDataToUpdate = ['name' => $request->nama_perusahaan];
            if ($request->filled('username')) $userDataToUpdate['username'] = $request->username;
            if ($request->filled('user_email_login')) $userDataToUpdate['email'] = $request->user_email_login;
            if ($request->filled('password')) $userDataToUpdate['password'] = Hash::make($request->password);
            
            if(count($userDataToUpdate) > 1 || $request->filled('password')) {
                $company->user->update($userDataToUpdate);
            }
        } elseif ($request->filled('new_username') && $request->filled('new_user_email') && $request->filled('new_password')) {
            $perusahaanRole = Role::where('name', 'perusahaan')->firstOrFail();
            $newUser = User::create([
                'name' => $request->nama_perusahaan,
                'email' => $request->new_user_email,
                'username' => $request->new_username,
                'password' => Hash::make($request->new_password),
                'role_id' => $perusahaanRole->id,
                'email_verified_at' => now(),
            ]);
            $company->user_id = $newUser->id;
            $company->save();
        }

        return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    /**
     * Menghapus perusahaan dari database.
     */
    public function destroy($companyId) // Menerima $companyId
    {
        $company = Company::findOrFail($companyId); // Mengambil company berdasarkan ID

        if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            Storage::disk('public')->delete($company->logo_path);
        }

        if ($company->user) {
            $company->user->delete();
        }

        $company->delete();
        return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan dan akun login terkait berhasil dihapus.');
    }
}