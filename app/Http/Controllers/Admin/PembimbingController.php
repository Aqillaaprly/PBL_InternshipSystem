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
    public function index()
    {
        // Ambil role dosen (asumsi pembimbing adalah dosen)
        $dosenRole = Role::where('name', 'dosen')->first();

        if (!$dosenRole) {
            return redirect()->route('admin.dashboard')->with('error', 'Role dosen tidak ditemukan.');
        }

        $pembimbings = User::where('role_id', $dosenRole->id)
                            ->orderBy('name')
                            ->paginate(15);

        // Pastikan view 'admin.pembimbing.index' atau 'admin.data_pembimbing' ada
        // Jika view Anda bernama 'admin.data_pembimbing.blade.php', maka gunakan 'admin.data_pembimbing'
        // Anda memiliki file 'admin.datapembimbing.blade.php' dan 'admin.data_pembimbing.blade.php', pastikan konsisten.
        // Saya akan menggunakan 'admin.data_pembimbing' sesuai nama route.
        return view('admin.data_pembimbing', compact('pembimbings'));
    }

    // Tambahkan method CRUD lainnya jika diperlukan
}
