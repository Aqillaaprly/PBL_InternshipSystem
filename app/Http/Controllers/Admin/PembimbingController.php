<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Asumsi pembimbing (dosen) adalah User
use App\Models\Role;

class PembimbingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Ini akan menangani route('admin.data_pembimbing')
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) // Tambahkan Request $request
    {
        $dosenRole = Role::where('name', 'dosen')->first();

        if (!$dosenRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Role dosen tidak ditemukan.');
        }

        $query = User::where('role_id', $dosenRole->id);

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%") // username (NIP)
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $pembimbings = $query->orderBy('name')->paginate(10)->withQueryString();


        return view('admin.Pembimbing.data_pembimbing', compact('pembimbings'));
    }

    // Tambahkan method CRUD lainnya jika diperlukan
}
