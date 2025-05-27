<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LowonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lowongans = Lowongan::with('company')->latest()->paginate(10); // Asumsi ada relasi 'company' di model Lowongan
        // Variabel $jumlahLowongan untuk dashboard
        $jumlahLowongan = Lowongan::count();
        return view('admin.lowongan', compact('lowongans', 'jumlahLowongan')); // resources/views/admin/lowongan.blade.php
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::orderBy('nama_perusahaan')->get(); // Untuk dropdown pilih perusahaan
        return view('admin.lowongan.create', compact('companies')); // Buat view ini: resources/views/admin/lowongan/create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tipe' => 'required|in:Penuh Waktu,Paruh Waktu,Kontrak,Internship',
            'lokasi' => 'required|string|max:255',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0|gte:gaji_min',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:Aktif,Ditutup',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        Lowongan::create($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lowongan $lowongan)
    {
        return view('admin.lowongan.show', compact('lowongan')); // Buat view ini: resources/views/admin/lowongan/show.blade.php
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lowongan $lowongan)
    {
        $companies = Company::orderBy('nama_perusahaan')->get();
        return view('admin.lowongan.edit', compact('lowongan', 'companies')); // Buat view ini: resources/views/admin/lowongan/edit.blade.php
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tipe' => 'required|in:Penuh Waktu,Paruh Waktu,Kontrak,Internship',
            'lokasi' => 'required|string|max:255',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0|gte:gaji_min',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:Aktif,Ditutup',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.edit', $lowongan->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $lowongan->update($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lowongan $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}