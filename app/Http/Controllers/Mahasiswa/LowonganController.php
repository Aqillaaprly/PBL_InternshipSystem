<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::with('company')
            ->whereHas('company', function($q) {
                $q->where('status_kerjasama', 'Aktif');
            });

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('lokasi', 'like', "%$search%")
                    ->orWhereHas('company', function($q) use ($search) {
                        $q->where('nama_perusahaan', 'like', "%$search%");
                    });
            });
        }

        // Filter by type
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $lowongans = $query->orderBy('created_at', 'desc')->paginate(10);
        $jumlahLowongan = Lowongan::count();

        return view('mahasiswa.lowongan', compact('lowongans', 'jumlahLowongan'));
    }

    public function create()
    {
        $companies = Company::orderBy('nama_perusahaan')->get();
        return view('mahasiswa.lowongan.create', compact('companies'));
    }

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
            return redirect()->route('mahasiswa.lowongan.create')
                ->withErrors($validator)
                ->withInput();
        }

        Lowongan::create($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show(Lowongan $lowongan)
    {
        return view('mahasiswa.lowongan.show', compact('lowongan'));
    }

    public function edit(Lowongan $lowongan)
    {
        $companies = Company::orderBy('nama_perusahaan')->get();
        return view('mahasiswa.lowongan.edit', compact('lowongan', 'companies'));
    }

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
            return redirect()->route('mahasiswa.lowongan.edit', $lowongan->id)
                ->withErrors($validator)
                ->withInput();
        }

        $lowongan->update($request->all());

        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Lowongan $lowongan)
    {
        $lowongan->delete();
        return redirect()->route('mahasiswa.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
