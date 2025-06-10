<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa; // Mahasiswa model might be used for direct operations if needed, but not directly in the show method here.
use App\Models\User; // Used for fetching user data
use App\Models\BimbinganMagang; // Used for fetching bimbingan data

class MahasiswaBimbinganController extends Controller
{
    /**
     * Display a listing of the bimbingan for supervised students.
     * Allows searching by student name or NIM.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get search query from request

        // Retrieve bimbingan records with eager loaded relationships for mahasiswa and their details
        $bimbingans = BimbinganMagang::with(['mahasiswa.detailMahasiswa'])
            // Conditionally apply search filter if a search term is provided
            ->when($search, function ($query, $search) {
                // Filter bimbingans where the related Mahasiswa's nama or NIM matches the search term
                $query->whereHas('mahasiswa.detailMahasiswa', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->paginate(10); // Paginate the results for better performance and UI

        // Return the view with the paginated bimbingan data
        return view('dosen.data_mahasiswabim', compact('bimbingans'));
    }

    /**
     * Display the specified student (User) with their detailed Mahasiswa information.
     *
     * @param \App\Models\User $mahasiswa The User model instance, resolved by Route Model Binding.
     * @return \Illuminate\View\View
     */
    public function show(User $mahasiswa)
    {
        // The $mahasiswa object is already resolved by Route Model Binding.
        // We only need to eager load its 'detailMahasiswa' relationship to access student-specific data
        // associated with this user, ensuring it's available in the view.
        $mahasiswa->load('detailMahasiswa');

        // Pass the resolved and loaded $mahasiswa object to the view.
        // Ensure the view 'dosen.showdataM' exists at resources/views/dosen/showdataM.blade.php
        return view('dosen.showdataM', compact('mahasiswa'));
    }
}
