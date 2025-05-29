<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class UserController extends Controller
{
    // Metode ini mungkin lebih cocok dinamai index() jika mengikuti konvensi resource controller
    public function view()
    {
        $users = User::with('role')->latest()->paginate(15); // Tambahkan paginasi dan urutan
        // Pastikan view ini ada: resources/views/admin/users/index.blade.php
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        // Pastikan view ini ada: resources/views/admin/users/create.blade.php
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Menggunakan Validator facade untuk pesan error yang lebih baik ke view
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // Tambahkan validasi untuk 'name'
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email', // Tambahkan validasi untuk 'email'
            'password' => 'required|string|min:6|confirmed', // 'confirmed' akan mencari field 'password_confirmation'
            'role_id' => 'required|exists:roles,id' // Ganti nullable menjadi required jika role wajib
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.create') // Sesuaikan dengan nama route
                        ->withErrors($validator)
                        ->withInput();
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        // Menggunakan redirect dengan pesan sukses untuk route web
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user) // Menggunakan Route Model Binding
    {
        $roles = Role::all();
        // Pastikan view ini ada: resources/views/admin/users/edit.blade.php
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user) // Menggunakan Route Model Binding
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.users.edit', $user->id)
                        ->withErrors($validator)
                        ->withInput();
        }

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

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }


    public function destroy(User $user) // Menggunakan Route Model Binding
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}