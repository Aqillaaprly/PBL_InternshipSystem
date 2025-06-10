<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with('user')->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_perusahaan', 'like', "%{$searchTerm}%")
                    ->orWhere('email_perusahaan', 'like', "%{$searchTerm}%")
                    ->orWhere('kota', 'like', "%{$searchTerm}%")
                    ->orWhere('provinsi', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('username', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $companies = $query->paginate(10)->withQueryString();

        return view('admin.Company.perusahaan', compact('companies'));
    }

    public function create()
    {
        return view('admin.Company.create');
    }

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
            // Modifikasi di sini: hapus whereNull('deleted_at')
            'telepon' => ['nullable', 'string', 'max:20', Rule::unique('companies', 'telepon')],
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

        $perusahaanRole = Role::where('name', 'perusahaan')->firstOrFail();

        DB::beginTransaction();
        try {
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
            DB::commit();

            return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan berhasil ditambahkan beserta akun loginnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating company: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine());
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            return redirect()->route('admin.perusahaan.create')
                ->with('error', 'Gagal menambahkan perusahaan. Silakan coba lagi. Error: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show(Company $company)
    {
        $company->load('user');

        return view('admin.Company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        $company->load('user');

        return view('admin.Company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $userIdToIgnore = $company->user ? $company->user->id : 0;

        $rules = [
            'nama_perusahaan' => ['required', 'string', 'max:255', Rule::unique('companies')->ignore($company->id)],
            'email_perusahaan' => ['required', 'string', 'email', 'max:255', Rule::unique('companies', 'email_perusahaan')->ignore($company->id)],
            'website' => 'required|url|max:255',
            'status_kerjasama' => 'required|in:Aktif,Non-Aktif,Review',
            // Modifikasi di sini: hapus whereNull('deleted_at')
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

        DB::beginTransaction();
        try {
            $companyData = $request->only([
                'nama_perusahaan', 'alamat', 'kota', 'provinsi', 'kode_pos',
                'telepon', 'email_perusahaan', 'website', 'deskripsi', 'status_kerjasama',
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
                if ($request->filled('username')) {
                    $userDataToUpdate['username'] = $request->username;
                }
                if ($request->filled('user_email_login')) {
                    $userDataToUpdate['email'] = $request->user_email_login;
                }

                if ($request->filled('password')) {
                    $userDataToUpdate['password'] = Hash::make($request->password);
                }

                $company->user->update($userDataToUpdate);

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
            DB::commit();

            return redirect()->route('admin.perusahaan.index')->with('success', 'Data perusahaan berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating company: '.$e->getMessage().' at '.$e->getFile().':'.$e->getLine());

            return redirect()->route('admin.perusahaan.edit', $company->id)
                ->with('error', 'Gagal memperbarui perusahaan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function destroy(Company $company)
    {
        DB::beginTransaction();
        try {
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }

            if ($company->user) {
                $company->user->delete();
            }
            $company->delete();

            DB::commit();

            return redirect()->route('admin.perusahaan.index')->with('success', 'Perusahaan dan akun login terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting company: '.$e->getMessage());

            return redirect()->route('admin.perusahaan.index')->with('error', 'Gagal menghapus perusahaan. Pastikan tidak ada data terkait (lowongan/pendaftar) atau handle relasi tersebut.');
        }
    }
}
