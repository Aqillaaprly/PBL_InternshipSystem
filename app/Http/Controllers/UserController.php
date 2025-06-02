<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa; // Pastikan model Mahasiswa di-import
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Untuk validasi unik yang lebih baik saat update

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // NAMA METHOD DIUBAH DARI view MENJADI index
    {
        $search = request('search');
        $users = User::with('role')
                       ->when($search, function ($query, $search) {
                           return $query->where('name', 'like', "%{$search}%")
                                        ->orWhere('username', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%");
                       })
                       ->latest()
                       ->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mahasiswaRoleId = Role::where('name', 'mahasiswa')->first()->id ?? null;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'kelas' => 'nullable|required_if:role_id,' . $mahasiswaRoleId . '|string|max:255',
            'program_studi' => 'nullable|required_if:role_id,' . $mahasiswaRoleId . '|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'email_verified_at' => now(),
        ]);

        if ($mahasiswaRoleId && $request->role_id == $mahasiswaRoleId) {
            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $user->username,
                'nama' => $user->name,
                'email' => $user->email,
                'kelas' => $request->kelas,
                'program_studi' => $request->program_studi,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route Model Binding akan bekerja dengan {user} dari Route::resource
    {
        $roles = Role::orderBy('name')->get();
        if ($user->role && $user->role->name === 'mahasiswa') {
            $user->load('detailMahasiswa');
        }
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Route Model Binding
    {
        $mahasiswaRoleId = Role::where('name', 'mahasiswa')->first()->id ?? null;

        $rules = [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        if ($request->role_id == $mahasiswaRoleId) {
            $rules['kelas'] = 'nullable|string|max:255';
            $rules['program_studi'] = 'nullable|string|max:255';
            $rules['nomor_hp'] = 'nullable|string|max:20';
            $rules['alamat'] = 'nullable|string';

            if ($user->detailMahasiswa) {
                // Memastikan NIM unik di tabel mahasiswas kecuali untuk dirinya sendiri
                $rules['username'][] = Rule::unique('mahasiswas', 'nim')->ignore($user->detailMahasiswa->id);
                $rules['email'][] = Rule::unique('mahasiswas', 'email')->ignore($user->detailMahasiswa->id);
            } else {
                 // Jika belum ada detailMahasiswa, username (NIM) dan email harus unik di tabel mahasiswas
                $rules['username'][] = Rule::unique('mahasiswas', 'nim');
                $rules['email'][] = Rule::unique('mahasiswas', 'email');
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.users.edit', $user->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $previousRoleId = $user->role_id;

        $userData = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->refresh(); 

        if ($request->role_id == $mahasiswaRoleId) {
            $mahasiswaDetailData = [
                'nim' => $request->username, // Menggunakan username sebagai NIM
                'nama' => $request->name,
                'email' => $request->email,
                'kelas' => $request->kelas,
                'program_studi' => $request->program_studi,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
            ];
            if ($user->detailMahasiswa) {
                $user->detailMahasiswa->update($mahasiswaDetailData);
            } else {
                // Buat detail mahasiswa jika belum ada
                Mahasiswa::create(array_merge(['user_id' => $user->id], $mahasiswaDetailData));
            }
        } elseif ($previousRoleId == $mahasiswaRoleId && $request->role_id != $mahasiswaRoleId) {
            // Jika role diubah DARI mahasiswa KE role lain, hapus detail mahasiswa jika ada
            if ($user->detailMahasiswa) {
                $user->detailMahasiswa->delete();
            }
        }

        // Logika Redirect yang lebih sesuai
        if ($user->role_id == $mahasiswaRoleId && $request->role_id == $mahasiswaRoleId) {
             // Jika role tetap mahasiswa atau diubah menjadi mahasiswa
            return redirect()->route('admin.datamahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
        } else {
            // Jika role adalah non-mahasiswa atau diubah menjadi non-mahasiswa
            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) // Route Model Binding
    {
        // Jika user adalah mahasiswa dan memiliki detailMahasiswa, detailnya juga akan terhapus
        // jika ada foreign key constraint dengan onDelete('cascade') di migrasi mahasiswas table untuk user_id.
        // Jika tidak ada cascade, Anda mungkin perlu menghapusnya secara manual:
        // if ($user->detailMahasiswa) {
        //     $user->detailMahasiswa->delete();
        // }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}