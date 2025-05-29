<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Mungkin Anda memerlukan model lain terkait laporan

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     * Ini akan menangani route('admin.laporan')
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.Mahasiswa.laporan'); // compact('laporans') jika ada data
    }

}
