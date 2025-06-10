<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use App\Models\Company; // Make sure to import the Company model

class ProfilePerusahaanController extends Controller
{
    /**
     * Display the perusahaan's profile.
     * Route: perusahaan.profile.perusahaanProfile
     */
    public function show()
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        // Check if a user is authenticated
        if (!$perusahaan) {
            // Handle case where user is not authenticated, e.g., redirect to login
            return redirect()->route('login'); // Or return an error view
        }

        // Fetch the company associated with the authenticated user
        // Assuming your User model has a hasOne('company') relationship
        $company = $perusahaan->company; // This will return null if no company is linked

        // Return the view, passing both the user and the company data
        return view('perusahaan.Profile.perusahaanProfile', compact('perusahaan', 'company'));
    }

    /**
     * Show the form for editing the perusahaan's profile.
     * Route: perusahaan.profile.edit
     */
    public function edit()
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        // Fetch the company data for the edit form if needed
        $company = $perusahaan->company;

        // Return the edit view
        return view('perusahaan.Profile.edit', compact('perusahaan', 'company'));
    }

    /**
     * Update the perusahaan's profile.
     * Route: perusahaan.profile.update
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $perusahaan */
        $perusahaan = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$perusahaan->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$perusahaan->id],
            'current_password' => ['nullable', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
            // You might also want to add validation rules for company data if you're updating it here
            // e.g., 'nama_perusahaan' => ['required', 'string', 'max:255'],
            // 'website' => ['nullable', 'url', 'max:255'],
            // 'about' => ['nullable', 'url', 'max:255'],
            // ... and so on for other company fields
        ];

        $request->validate($rules);

        // Validate current_password if new_password is filled
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $perusahaan->password)) {
                throw ValidationException::withMessages([
                    'current_password' => __('auth.password'),
                ]);
            }
        }

        $perusahaan->name = $request->name;
        $perusahaan->username = $request->username;
        $perusahaan->email = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($perusahaan->profile_picture && Storage::disk('public')->exists($perusahaan->profile_picture)) {
                Storage::disk('public')->delete($perusahaan->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('profile_pictures', $filename, 'public');
            $perusahaan->profile_picture = $path;
        }

        if ($request->filled('new_password')) {
            $perusahaan->password = Hash::make($request->new_password);
        }

        $perusahaan->save();

        // If you also want to update company data, you'd do it here:
        // if ($perusahaan->company) {
        //     $perusahaan->company->update([
        //         'nama_perusahaan' => $request->nama_perusahaan,
        //         'website' => $request->website,
        //         'about' => $request->about,
        //         // ... update other company fields
        //     ]);
        // }


        // Fixed redirect to match the route name
        return redirect()->route('perusahaan.profile.perusahaanProfile2')->with('success', 'Profil berhasil diperbarui.');
    }
}