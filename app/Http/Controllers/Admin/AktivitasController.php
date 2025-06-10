<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagang;
use App\Models\Mahasiswa;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Role; // Pastikan model Role diimport
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil ID role mahasiswa dengan aman
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswaRoleId = $mahasiswaRole ? $mahasiswaRole->id : null;

        // Jika role 'mahasiswa' tidak ditemukan, mungkin tidak ada mahasiswa yang sesuai
        if (is_null($mahasiswaRoleId)) {
            // Anda bisa memilih untuk mengembalikan view dengan data kosong
            // atau pesan error, atau redirect. Untuk saat ini, kita kembalikan paginasi kosong.
            $mahasiswas = (new User())->newQuery()->paginate(10); // Membuat paginator kosong
            return view('admin.Mahasiswa.aktivitas_absensi', compact('mahasiswas'))->with('error', 'Role "mahasiswa" tidak ditemukan. Pastikan data role sudah ada.');
        }

        $query = User::where('role_id', $mahasiswaRoleId)
            ->whereHas('pendaftars', function ($q) {
                $q->where('status_lamaran', 'Diterima');
            })
            ->with(['detailMahasiswa', 'pendaftars.lowongan.company']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $mahasiswas = $query->orderBy('name')->paginate(10);

        return view('admin.Mahasiswa.aktivitas_absensi', compact('mahasiswas'));
    }

    public function show($mahasiswa_id)
    {
        $mahasiswa = User::with(['detailMahasiswa', 'pendaftars.lowongan.company'])->findOrFail($mahasiswa_id);

        // Pastikan relasi AktivitasMagang ke Mahasiswa dan Mahasiswa ke User sudah benar
        $aktivitas = AktivitasMagang::whereHas('mahasiswa', function ($query) use ($mahasiswa) {
                                            $query->where('user_id', $mahasiswa->id);
                                        })
                                     ->with(['mahasiswa.user', 'dosenPembimbing.user', 'perusahaanPic.user'])
                                     ->orderBy('tanggal', 'asc')
                                     ->get();

        return view('admin.Mahasiswa.detail_aktivitas', compact('mahasiswa', 'aktivitas'));
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

        return redirect()->route('admin.aktivitas-mahasiswa.show', $aktivitas->mahasiswa->user_id)
                         ->with('success', 'Aktivitas berhasil diverifikasi.');
    }
}