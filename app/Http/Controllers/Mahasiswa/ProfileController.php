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
        /** @var \App\Models\User $mahasiswa */
        $mahasiswa = Auth::user();
        return view('mahasiswa.mahasiswaProfile', compact('mahasiswa'));
    }

    /**
     * Tampilkan form edit profil mahasiswa.
     */
    public function edit()
    {
        /** @var \App\Models\User $mahasiswa */
        $mahasiswa = Auth::user();
        return view('mahasiswa.Profile.edit', compact('mahasiswa'));
    }

    /**
     * Proses update profil mahasiswa.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $mahasiswa */
        $mahasiswa = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $mahasiswa->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $mahasiswa->id],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];

        $request->validate($rules);

        // Jika mengisi new_password, validasi current_password
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $mahasiswa->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('auth.password'),
                ]);
            }
        }

        $mahasiswa->name = $request->name;
        $mahasiswa->username = $request->username;
        $mahasiswa->email = $request->email;

        // Upload foto profil
        if ($request->hasFile('profile_picture')) {
            if ($mahasiswa->profile_picture && Storage::disk('public')->exists($mahasiswa->profile_picture)) {
                Storage::disk('public')->delete($mahasiswa->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $mahasiswa->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            $mahasiswa->password = Hash::make($request->new_password);
        }

        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
