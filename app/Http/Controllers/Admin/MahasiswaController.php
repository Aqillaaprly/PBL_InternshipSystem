<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // Tambahkan ini
use App\Models\User;
use App\Models\Role;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        if (!$mahasiswaRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Role mahasiswa tidak ditemukan.');
        }

         $query = User::with('detailMahasiswa')->where('role_id', $mahasiswaRole->id);

        // Logika Pencarian (mencari di kolom username yang sekarang dianggap NIM)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%") // username (NIM)
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $mahasiswas = $query->orderBy('name')->paginate(15)->withQueryString();

         return view('admin.Mahasiswa.datamahasiswa', compact('mahasiswas'));
    }
}