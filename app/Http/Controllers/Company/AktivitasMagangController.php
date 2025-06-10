<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagang;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Role; // Pastikan model Role diimport
use Illuminate\Http\Request;

class AktivitasMagangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Ambil ID role pendaftar dengan aman
        $mahasiswaRole = Role::where('name', 'pendaftar')->first();
        $mahasiswaRoleId = $mahasiswaRole ? $mahasiswaRole->id : null;
        
        // Jika role 'pendaftar' tidak ditemukan
        if (is_null($mahasiswaRoleId)) {
            // Buat paginator kosong dengan struktur yang benar
            $pendaftars = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // Empty collection
                0, // Total items
                10, // Items per page
                1, // Current page
                ['path' => request()->url(), 'pageName' => 'page']
            );
            
            return view('perusahaan.aktivitas_magang', compact('pendaftars'))
                ->with('error', 'Role "pendaftar" tidak ditemukan. Pastikan data role sudah ada.');
        }
        
        // Query untuk mendapatkan pendaftar, bukan user
        $query = Pendaftar::whereHas('user', function ($q) use ($mahasiswaRoleId) {
                $q->where('role_id', $mahasiswaRoleId);
            })
            ->where('status_lamaran', 'Diterima')
            ->with(['user', 'lowongan.company']);
        
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $pendaftars = $query->orderBy('tanggal_daftar', 'desc')->paginate(10);
        
        return view('perusahaan.aktivitas_magang', compact('pendaftars'));
    }
    
    public function show($pendaftar_id)
    {
        $pendaftar = Pendaftar::with(['user.detailMahasiswa', 'lowongan.company'])->findOrFail($pendaftar_id);
        
        // Pastikan relasi AktivitasMagang ke Pendaftar sudah benar
        $aktivitas = AktivitasMagang::where('pendaftar_id', $pendaftar_id)
            ->with(['pendaftar.user', 'dosenPembimbing.user', 'perusahaanPic.user'])
            ->orderBy('tanggal', 'asc')
            ->get();
        
        return view('perusahaan.Mahasiswa.detail_aktivitas', compact('pendaftar', 'aktivitas'));
    }
    
    public function verify(Request $request, $id)
    {
        $aktivitas = AktivitasMagang::findOrFail($id);
        
        $request->validate([
            'status_verifikasi' => 'required|in:pending,terverifikasi,ditolak',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);
        
        $aktivitas->status_verifikasi = $request->input('status_verifikasi');
        $aktivitas->catatan_dosen = $request->input('catatan_dosen');
        $aktivitas->save();
        
        return redirect()->route('perusahaan.aktivitas-pendaftar.show', $aktivitas->pendaftar_id)
            ->with('success', 'Aktivitas berhasil diverifikasi.');
    }
}