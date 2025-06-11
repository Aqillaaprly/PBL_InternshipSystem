<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil mahasiswa yang sedang login.
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user()->load('role', 'detailMahasiswa');
        return view('mahasiswa.mahasiswaProfile', compact('user'));
    }

    /**
     * Tampilkan form edit profil mahasiswa.
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user()->load('role', 'detailMahasiswa');
        return view('mahasiswa.Profile.edit', compact('user'));
    }

    /**
     * Proses update profil mahasiswa.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];

        $validatedData = $request->validate($rules);

        // Password validation
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('The current password is incorrect.'),
                ]);
            }
            $validatedData['password'] = Hash::make($request->new_password);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $path;
        }

        // Update user data
        $user->update($validatedData);

        return redirect()->route('mahasiswa.profile')->with('success', 'Profile updated successfully.');
    }
}
