<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\Pembimbing; 

class ProfileController extends Controller
{
    /**
     * Display the dosen's profile.
     * Route: dosen.profile.dosenProfile
     */
    public function show()
    {
        /** @var \App\Models\User $dosen */
        $dosen = Auth::user();

        // Check if a user is authenticated
        if (!$dosen) {
            // Handle case where user is not authenticated, e.g., redirect to login
            return redirect()->route('login'); // Or return an error view
        }

        // Fetch the dosen associated with the authenticated user
        // Assuming your User model has a hasOne('dosen') relationship
        $dosen = $dosen->dosen; // This will return null if no dosen is linked

        // Return the view, passing both the user and the dosen data
        return view('dosen.Profile.dosenProfile', compact('dosen', 'dosen'));
    }

    /**
     * Show the form for editing the dosen's profile.
     * Route: dosen.profile.edit
     */
    public function edit()
    {
        /** @var \App\Models\User $dosen */
        $dosen = Auth::user();

        // Fetch the dosen data for the edit form if needed
        $dosen = $dosen->dosen;

        // Return the edit view
        return view('dosen.Profile.edit', compact('dosen', 'dosen'));
    }

    /**
     * Update the dosen's profile.
     * Route: dosen.profile.update
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $dosen */
        $dosen = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$dosen->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$dosen->id],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            // You might also want to add validation rules for dosen data if you're updating it here
            // e.g., 'nama_dosen' => ['required', 'string', 'max:255'],
            // 'website' => ['nullable', 'url', 'max:255'],
            // 'about' => ['nullable', 'url', 'max:255'],
            // ... and so on for other dosen fields
        ];

        $request->validate($rules);

        // Validate current_password if new_password is filled
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $dosen->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('auth.password'),
                ]);
            }
        }

        $dosen->name = $request->name;
        $dosen->username = $request->username;
        $dosen->email = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($dosen->profile_picture && Storage::disk('public')->exists($dosen->profile_picture)) {
                Storage::disk('public')->delete($dosen->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $dosen->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            $dosen->password = Hash::make($request->new_password);
        }

        $dosen->save();

        return redirect()->route('dosen.profile.dosenProfile2')->with('success', 'Profil berhasil diperbarui.');
    }






}