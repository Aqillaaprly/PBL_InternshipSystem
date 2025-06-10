<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BimbinganMagang; // Assuming you have this model
use App\Models\User; // To fetch mahasiswa users
use App\Models\Pembimbing; // To fetch pembimbing data
use App\Models\Pendaftar; // Import Pendaftar model to get lowongan_id and company_id
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BimbinganController extends Controller
{
    public function create()
    {
        $mahasiswaUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'mahasiswa');
        })->with('detailMahasiswa')->get();

        $pembimbings = Pembimbing::with('user')->get(); // Get all pembimbings

        return view('admin.bimbingan.create', compact('mahasiswaUsers', 'pembimbings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mahasiswa_user_id' => 'required|exists:users,id',
            'pembimbing_id' => 'required|exists:pembimbings,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Check if the student already has an active bimbingan
            $existingBimbingan = BimbinganMagang::where('mahasiswa_user_id', $request->mahasiswa_user_id)
                                                ->where('status_bimbingan', 'Aktif')
                                                ->first();

            if ($existingBimbingan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Mahasiswa ini sudah memiliki bimbingan magang yang aktif.')->withInput();
            }

            $pembimbing = Pembimbing::find($request->pembimbing_id);
            if (!$pembimbing) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pembimbing tidak ditemukan.')->withInput();
            }

            if ($pembimbing->kuota_aktif >= $pembimbing->maks_kuota_bimbingan) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Kuota bimbingan pembimbing ini sudah penuh.')->withInput();
            }

            // Find the associated Pendaftar entry for the student
            // Assuming 'Diterima' status indicates an accepted internship for which bimbingan will be assigned.
            // You might need to adjust the criteria (e.g., 'sedang_berjalan' or 'active' status)
            $pendaftar = Pendaftar::where('mahasiswa_user_id', $request->mahasiswa_user_id)
                                ->where('status', 'Diterima') // Adjust this status as per your application logic
                                ->with('lowongan.company') // Eager load lowongan and its company
                                ->latest() // Get the latest accepted application if multiple exist
                                ->first();

            if (!$pendaftar) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Mahasiswa belum memiliki pendaftaran magang berstatus Diterima.')->withInput();
            }

            // Extract lowongan_id and company_id from the Pendaftar entry
            $lowonganId = $pendaftar->lowongan_id;
            $companyId = $pendaftar->lowongan->company_id ?? null; // Ensure company_id is available from lowongan

            if (is_null($lowonganId)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Pendaftaran mahasiswa tidak memiliki ID lowongan yang valid.')->withInput();
            }

            BimbinganMagang::create([
                'mahasiswa_user_id' => $request->mahasiswa_user_id,
                'pembimbing_id' => $request->pembimbing_id,
                'lowongan_id' => $lowonganId, // Provide lowongan_id
                'company_id' => $companyId,   // Provide company_id
                'status_bimbingan' => 'Aktif',
                'tanggal_mulai' => now(), // Or adjust as needed
                'tanggal_selesai' => null, // Will be set when internship ends
            ]);

            // Increment active quota
            $pembimbing->increment('kuota_aktif');

            DB::commit();
            return redirect()->route('admin.pembimbings.index')->with('success', 'Bimbingan magang berhasil ditetapkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing bimbingan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menetapkan bimbingan magang: ' . $e->getMessage())->withInput();
        }
    }

    // placeholder methods for edit, update, destroy as defined in routes/web.php
    public function edit(BimbinganMagang $bimbinganMagang)
    {
        // You'll need to implement the logic to load the bimbinganMagang
        // and any other data needed for the edit form (e.g., all students, all pembimbings)
        // For now, it's just a placeholder to prevent route errors.
        return view('admin.bimbingan.edit', compact('bimbinganMagang'));
    }

    public function update(Request $request, BimbinganMagang $bimbinganMagang)
    {
        // Implement validation and update logic here
        // For now, it's just a placeholder.
        return redirect()->route('admin.pembimbings.index')->with('success', 'Bimbingan Magang berhasil diperbarui.');
    }

    public function destroy(BimbinganMagang $bimbinganMagang)
    {
        try {
            DB::beginTransaction();

            $pembimbing = $bimbinganMagang->pembimbing; // Get the related pembimbing
            $bimbinganMagang->delete();

            // Decrement active quota if the bimbingan was active
            if ($pembimbing && $pembimbing->kuota_aktif > 0) {
                $pembimbing->decrement('kuota_aktif');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Bimbingan magang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting bimbingan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus bimbingan magang: ' . $e->getMessage());
        }
    }
}
