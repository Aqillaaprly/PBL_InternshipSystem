<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Asumsi mahasiswa adalah User
use App\Models\Role;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Ini akan menangani route('admin.datamahasiswa')
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil role mahasiswa
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        if (!$mahasiswaRole) {
            // Handle jika role mahasiswa tidak ditemukan, mungkin redirect atau tampilkan error
            return redirect()->route('admin.dashboard')->with('error', 'Role mahasiswa tidak ditemukan.');
        }

        $mahasiswas = User::where('role_id', $mahasiswaRole->id)
                            ->orderBy('name') // Urutkan berdasarkan nama
                            ->paginate(15); // Gunakan paginasi

        // Pastikan view 'admin.mahasiswa.index' atau 'admin.datamahasiswa' ada
        // Jika view Anda bernama 'admin.datamahasiswa.blade.php', maka gunakan 'admin.datamahasiswa'
        return view('admin.datamahasiswa', compact('mahasiswas')); 
    }

    // Anda bisa menambahkan method lain seperti create, store, edit, update, destroy
    // jika admin memiliki hak untuk mengelola data mahasiswa secara penuh.
    // Contoh:
    // public function show(User $mahasiswa)
    // {
    //     // Pastikan user yang diambil adalah mahasiswa
    //     if ($mahasiswa->role->name !== 'mahasiswa') {
    //         abort(404);
    //     }
    //     return view('admin.mahasiswa.show', compact('mahasiswa'));
    // }
}
