<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile.
     * Ini akan menangani route('admin.profile')
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $admin = Auth::user();
        // Pastikan view 'admin.profile.show' atau 'admin.adminProfile' ada
        // Anda memiliki file 'adminProfile.php' di 'company/template' dan 'mahasiswa',
        // namun belum ada untuk admin secara spesifik.
        // Asumsi Anda akan membuat 'admin.profile.blade.php' atau 'admin.adminProfile.blade.php'
        return view('admin.adminProfile', compact('admin')); // Ganti 'admin.adminProfile' dengan nama view yang benar
    }

    /**
     * Show the form for editing the admin's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $admin = Auth::user();
        // Asumsi Anda akan membuat 'admin.profile-edit.blade.php'
        return view('admin.profile-edit', compact('admin'));
    }

    /**
     * Update the admin's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $admin->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;

        if ($request->filled('new_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
