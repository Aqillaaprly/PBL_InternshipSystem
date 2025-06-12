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
        // Fetch lowongans with their associated company, ordered by the latest created, and paginate the results.
        $lowongans = Lowongan::with('company')->latest()->paginate(10);

        // Return the view for displaying lowongan listings, passing the lowongans data.
        return view('admin.Company.lowongan.index', compact('lowongans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch all companies, ordered by their name, to be used in the lowongan creation form.
        $companies = Company::orderBy('nama_perusahaan')->get();

        // Return the view for creating a new lowongan, passing the companies data.
        return view('admin.Company.lowongan.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        // The 'status' field must be 'Aktif' or 'Non-Aktif'.
        // The 'tipe' field must be one of the specified enum values, now including 'Internship'.
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tipe' => 'required|in:Full-time,Part-time,Magang,Kontrak,Internship', // Corrected: Added 'Internship'
            'lokasi' => 'required|string|max:255',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0|gte:gaji_min',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:Aktif,Non-Aktif', // Existing validation
        ]);

        // If validation fails, redirect back with errors and input.
        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Create a new Lowongan record using all validated request data.
        Lowongan::create($request->all());

        // Redirect to the lowongan index page with a success message.
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lowongan $lowongan)
    {
        // Load the associated company for the given lowongan.
        $lowongan->load('company');

        // Return the view for displaying lowongan details, passing the lowongan data.
        return view('admin.Company.lowongan.show', compact('lowongan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lowongan $lowongan)
    {
        // Fetch all companies, ordered by their name, to be used in the lowongan edit form.
        $companies = Company::orderBy('nama_perusahaan')->get();

        // Return the view for editing a lowongan, passing both lowongan and companies data.
        return view('admin.Company.lowongan.edit', compact('lowongan', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lowongan $lowongan)
    {
        // Validate the incoming request data for updating a lowongan.
        // The 'status' field must be 'Aktif' or 'Non-Aktif'.
        // The 'tipe' field must be one of the specified enum values, now including 'Internship'.
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'tipe' => 'required|in:Full-time,Part-time,Magang,Kontrak,Internship', // Corrected: Added 'Internship'
            'lokasi' => 'required|string|max:255',
            'gaji_min' => 'nullable|numeric|min:0',
            'gaji_max' => 'nullable|numeric|min:0|gte:gaji_min',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:Aktif,Non-Aktif', // Existing validation
        ]);

        // If validation fails, redirect back to the edit form with errors and input.
        if ($validator->fails()) {
            return redirect()->route('admin.lowongan.edit', $lowongan->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update the Lowongan record with all validated request data.
        $lowongan->update($request->all());

        // Redirect to the lowongan index page with a success message.
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lowongan $lowongan)
    {
        // Delete the specified lowongan record.
        $lowongan->delete();

        // Redirect to the lowongan index page with a success message.
        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}

