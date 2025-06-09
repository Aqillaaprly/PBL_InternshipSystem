<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Pastikan ini di-import jika menggunakan Password::defaults()
use Illuminate\Validation\Rules\Password; // Untuk menangani validasi current_password secara manual
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile.
     * Ini akan menangani route('admin.profile')
     */
    public function show()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Pastikan view ini ada: resources/views/admin/adminProfile.blade.php
        return view('admin.Profile.adminProfile', compact('admin'));
    }

    /**
     * Show the form for editing the admin's profile.
     */
    public function edit()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Pastikan view ini ada: resources/views/admin/profile-edit.blade.php
        return view('admin.Profile.edit', compact('admin'));
    }

    /**
     * Update the admin's profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$admin->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$admin->id],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'], // Validasi file gambar
        ];

        $request->validate($rules);

        // Validasi current_password jika new_password diisi
        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $admin->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('auth.password'),
                ]);
            }
        }

        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;

        // Handle upload foto profil
        if ($request->hasFile('profile_picture')) {
            // Hapus foto profil lama jika ada
            if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                Storage::disk('public')->delete($admin->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public'); // Simpan di storage/app/public/profile_pictures
            $admin->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
