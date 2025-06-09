<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LowonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ... (logika query Anda) ...
        $lowongans = Lowongan::with('company')->latest()->paginate(10); // Contoh sederhana

        // Jika file index ada di admin/Company/lowongan/index.blade.php
        return view('admin.Company.lowongan.index', compact('lowongans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::orderBy('nama_perusahaan')->get();

        // Jika file create ada di admin/Company/lowongan/create.blade.php
        return view('admin.Company.lowongan.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Aktif,Non-Aktif', // Validasi status
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.create')
                ->withErrors($validator)
                ->withInput();
        }

        Lowongan::create($request->all()); // $request->all() akan mencakup 'status'

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lowongan $lowongan)
    {
        $lowongan->load('company');

        // Path view disesuaikan dengan struktur folder Anda
        return view('admin.Company.lowongan.show', compact('lowongan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lowongan $lowongan)
    {
        $companies = Company::orderBy('nama_perusahaan')->get();

        // Path view disesuaikan dengan struktur folder Anda
        return view('admin.Company.lowongan.edit', compact('lowongan', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Aktif,Non-Aktif', // Ubah di sini untuk menyertakan 'Non-Aktif'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.edit', $lowongan->id)
                ->withErrors($validator)
                ->withInput();
        }

        $lowongan->update($request->all()); // $request->all() akan mencakup 'status'

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lowongan $lowongan)
    {
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
