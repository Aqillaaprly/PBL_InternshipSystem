<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Import Rule

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if (! $mahasiswaRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Role mahasiswa tidak ditemukan.');
        }
        $query = User::with('detailMahasiswa')->where('role_id', $mahasiswaRole->id);
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('username', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        $mahasiswas = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.Mahasiswa.datamahasiswa', compact('mahasiswas'));
    }

    public function create()
    {
        return view('admin.Mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:255|unique:users,username|unique:mahasiswas,nim',
            'email' => 'required|string|email|max:255|unique:users,email|unique:mahasiswas,email',
            'password' => 'required|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mahasiswa.create')
                ->withErrors($validator)
                ->withInput();
        }
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if (! $mahasiswaRole) {
            return redirect()->route('admin.mahasiswa.create')->with('error', 'Role mahasiswa tidak ditemukan.')->withInput();
        }
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->nim,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $mahasiswaRole->id,
            'email_verified_at' => now(),
        ]);
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas' => $request->kelas,
            'program_studi' => $request->program_studi,
            'nomor_hp' => $request->nomor_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.datamahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(User $mahasiswa)
    {
        if ($mahasiswa->role->name !== 'mahasiswa') {
            abort(404, 'User bukan mahasiswa.');
        }
        $mahasiswa->load('detailMahasiswa');

        return view('admin.Mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified student.
     * Parameter $mahasiswa adalah instance User.
     */
    public function edit(User $mahasiswa)
    {
        if (! $mahasiswa->role || $mahasiswa->role->name !== 'mahasiswa') {
            abort(404, 'User yang akan diedit bukan mahasiswa.');
        }
        $mahasiswa->load('detailMahasiswa'); // Load detail mahasiswa

        // View ada di resources/views/admin/Mahasiswa/edit.blade.php
        return view('admin.Mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified student in storage.
     * Parameter $mahasiswa adalah instance User.
     */
    public function update(Request $request, User $mahasiswa)
    {
        if (! $mahasiswa->role || $mahasiswa->role->name !== 'mahasiswa') {
            abort(403, 'Tidak diizinkan mengubah user yang bukan mahasiswa melalui endpoint ini.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => [ // Ini adalah NIM
                'required', 'string', 'max:255',
                Rule::unique('users')->ignore($mahasiswa->id),
                // Pastikan NIM unik di tabel mahasiswas, kecuali untuk dirinya sendiri
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->detailMahasiswa->id ?? null),
            ],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($mahasiswa->id),
                Rule::unique('mahasiswas', 'email')->ignore($mahasiswa->detailMahasiswa->id ?? null),
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mahasiswa.edit', $mahasiswa->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update data di tabel users
        $userData = [
            'name' => $request->name,
            'username' => $request->username, // NIM
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $mahasiswa->update($userData);

        // Update atau buat data di tabel mahasiswas
        if ($mahasiswa->detailMahasiswa) {
            $mahasiswa->detailMahasiswa->update([
                'nim' => $request->username, // Sinkronkan NIM
                'nama' => $request->name,   // Sinkronkan nama
                'email' => $request->email, // Sinkronkan email
                'kelas' => $request->kelas,
                'program_studi' => $request->program_studi,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
            ]);
        } else {
            // Seharusnya tidak terjadi jika alur datanya benar, tapi sebagai fallback
            Mahasiswa::create([
                'user_id' => $mahasiswa->id,
                'nim' => $request->username,
                'nama' => $request->name,
                'email' => $request->email,
                'kelas' => $request->kelas,
                'program_studi' => $request->program_studi,
                'nomor_hp' => $request->nomor_hp,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->route('admin.datamahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student from storage.
     * Parameter $mahasiswa adalah instance User.
     */
    public function destroy(User $mahasiswa)
    {
        if (! $mahasiswa->role || $mahasiswa->role->name !== 'mahasiswa') {
            abort(403, 'Tidak diizinkan menghapus user yang bukan mahasiswa melalui endpoint ini.');
        }
        // Menghapus User akan otomatis menghapus Mahasiswa detail jika onDelete('cascade') diset
        $mahasiswa->delete();

        return redirect()->route('admin.datamahasiswa')->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
