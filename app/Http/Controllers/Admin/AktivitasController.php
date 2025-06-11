<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasMagang;
use App\Models\BimbinganMagang;
use App\Models\Mahasiswa; // Pastikan model Mahasiswa diimport jika digunakan secara langsung
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Role; // Pastikan model Role diimport
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        // $data = BimbinganMagang::with('pembimbing', 'company', 'mahasiswa')->get(); // Variabel ini tidak digunakan, bisa dihapus

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

    public function show($id)
    {
        // Eager load bimbingan magang beserta relasi mahasiswa dan detail pendaftarannya
        // agar informasi perusahaan juga tersedia di view.
        $bimbingan = BimbinganMagang::with([
            'pembimbing',
            'company',
            'mahasiswa' => function ($query) {
                $query->with(['pendaftars.lowongan.company']); // Memuat relasi pendaftars, lowongan, dan company dari mahasiswa
            }
        ])->findOrFail($id);
        
        // Dapatkan objek Mahasiswa dari relasi bimbingan
        // Ini akan menjadi variabel $mahasiswa yang dibutuhkan di view
        $mahasiswa = $bimbingan->mahasiswa;

        // Ambil semua aktivitas magang mahasiswa yang terkait dengan mahasiswa ini
        $aktivitas = AktivitasMagang::where('mahasiswa_id', $mahasiswa->id)
                                    ->orderBy('tanggal', 'desc')
                                    ->get();

        // Teruskan variabel $bimbingan, $aktivitas, dan $mahasiswa ke view
        return view('admin.Mahasiswa.detail_aktivitas', compact('bimbingan', 'aktivitas', 'mahasiswa'));
    }

    public function verify(Request $request, $id)
    {
        $aktivitas = AktivitasMagang::findOrFail($id);
        
        $request->validate([
            'status_verifikasi' => 'required|in:pending,terverifikasi,ditolak',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        $aktivitas->status_verifikasi = $request->input('status_verifikasi');
        $aktivitas->catatan_dosen = $request->input('catatan_dosen'); // Menggunakan catatan_dosen sesuai field di controller
        $aktivitas->save();

        // Periksa apakah $aktivitas->mahasiswa tersedia sebelum redirect
        if ($aktivitas->mahasiswa && $aktivitas->mahasiswa->user_id) {
            return redirect()->route('admin.aktivitas-mahasiswa.show', $aktivitas->mahasiswa->user_id)
                             ->with('success', 'Aktivitas berhasil diverifikasi.');
        } else {
            // Handle case where mahasiswa or user_id is not found
            return redirect()->back()->with('error', 'Gagal memverifikasi aktivitas: Data mahasiswa tidak ditemukan.');
        }
    }
}

