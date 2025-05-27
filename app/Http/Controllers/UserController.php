<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function view()
    {
        $users = User::with('role')->get();
        return view('admin.dashboard', compact('users')); // kirim variabel $users ke view
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.UserCreate', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
            'role_id' => 'nullable|exists:roles,id'
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);

        return response()->json(['success' => true]);
        //return redirect('/userdata')->with('success', 'User created successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Jika kamu punya relasi role
        return view('admin.edit', compact('user', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6', // hanya divalidasi jika diisi
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->role_id = $request->role_id;

        // Jika password diisi, hash dan simpan
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('userdata.index')->with('success', 'User berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('userdata.index')->with('success', 'User berhasil dihapus.');
    }
}
