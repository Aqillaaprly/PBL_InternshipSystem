<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BimbinganMagang;
use App\Models\Company;
use App\Models\Pembimbing;
use App\Models\Pendaftar;
use App\Models\User; // Untuk mengambil info mahasiswa yang diterima
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenugasanPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = BimbinganMagang::with(['mahasiswa.detailMahasiswa', 'pembimbing.user', 'company'])
            ->latest();

        if ($request->filled('search_mahasiswa')) {
            $search = $request->search_mahasiswa;
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%"); // Asumsi username adalah NIM
            });
        }
        if ($request->filled('search_pembimbing')) {
            $search = $request->search_pembimbing;
            $query->whereHas('pembimbing.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('pembimbing', function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%");
            });
        }

        $penugasan = $query->paginate(15)->withQueryString();

        return view('admin.penugasan_pembimbing.index', compact('penugasan'));
    }

    public function create()
    {
        // Ambil mahasiswa yang sudah diterima magang tapi belum punya pembimbing aktif
        // Ini logika yang lebih kompleks, untuk sementara kita ambil semua mahasiswa dan dosen
        $mahasiswas = User::whereHas('role', fn ($q) => $q->where('name', 'mahasiswa'))
            ->whereDoesntHave('bimbinganMagangSebagaiMahasiswa', fn ($q) => $q->where('status_bimbingan', 'Aktif'))
            ->orderBy('name')->get();

        $pembimbings = Pembimbing::where('status_aktif', true)
            ->with('user') // Untuk menampilkan nama dari User
            ->whereRaw('kuota_bimbingan_aktif < maks_kuota_bimbingan')
            ->get()
            ->sortBy(function ($pembimbing) {
                return $pembimbing->user->name ?? $pembimbing->nama_lengkap;
            });

        // Opsional: Ambil info pendaftar yang diterima untuk prefill company/lowongan
        $pendaftarDiterima = Pendaftar::where('status_lamaran', 'Diterima')
            ->with(['user', 'lowongan.company'])
            ->get();

        return view('admin.penugasan_pembimbing.create', compact('mahasiswas', 'pembimbings', 'pendaftarDiterima'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mahasiswa_user_id' => [
                'required',
                'exists:users,id',
                // Pastikan mahasiswa belum punya bimbingan aktif
                Rule::unique('bimbingan_magangs')->where(function ($query) use ($request) {
                    return $query->where('mahasiswa_user_id', $request->mahasiswa_user_id)
                        ->where('status_bimbingan', 'Aktif');
                }),
            ],
            'pembimbing_id' => 'required|exists:pembimbings,id',
            'company_id' => 'nullable|exists:companies,id',
            'lowongan_id' => 'nullable|exists:lowongans,id',
            'periode_magang' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status_bimbingan' => 'required|in:Aktif,Selesai,Dibatalkan',
            'catatan_koordinator' => 'nullable|string',
        ], [
            'mahasiswa_user_id.unique' => 'Mahasiswa tersebut sudah memiliki bimbingan magang yang aktif.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.penugasan-pembimbing.create')
                ->withErrors($validator)
                ->withInput();
        }

        $pembimbing = Pembimbing::find($request->pembimbing_id);
        if ($pembimbing->kuota_bimbingan_aktif >= $pembimbing->maks_kuota_bimbingan) {
            return redirect()->route('admin.penugasan-pembimbing.create')
                ->with('error', 'Kuota bimbingan untuk dosen pembimbing ini sudah penuh.')
                ->withInput();
        }

        BimbinganMagang::create($request->all());

        // Increment kuota_bimbingan_aktif
        $pembimbing->increment('kuota_bimbingan_aktif');

        return redirect()->route('admin.penugasan-pembimbing.index')->with('success', 'Penugasan pembimbing berhasil ditambahkan.');
    }

    public function edit(BimbinganMagang $penugasan_pembimbing) // Laravel akan resolve BimbinganMagang dari ID
    {
        $mahasiswas = User::whereHas('role', fn ($q) => $q->where('name', 'mahasiswa'))
            ->orderBy('name')->get();
        $pembimbings = Pembimbing::where('status_aktif', true)
            ->with('user')
            ->get()
            ->sortBy(function ($pembimbing) {
                return $pembimbing->user->name ?? $pembimbing->nama_lengkap;
            });
        $companies = Company::orderBy('nama_perusahaan')->get();

        return view('admin.penugasan_pembimbing.edit', compact('penugasan_pembimbing', 'mahasiswas', 'pembimbings', 'companies'));
    }

    public function update(Request $request, BimbinganMagang $penugasan_pembimbing)
    {
        $validator = Validator::make($request->all(), [
            // mahasiswa_user_id tidak boleh diubah karena ini adalah record bimbingan spesifik
            'pembimbing_id' => 'required|exists:pembimbings,id',
            'company_id' => 'nullable|exists:companies,id',
            'lowongan_id' => 'nullable|exists:lowongans,id',
            'periode_magang' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status_bimbingan' => 'required|in:Aktif,Selesai,Dibatalkan',
            'catatan_koordinator' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.penugasan-pembimbing.edit', $penugasan_pembimbing->id)
                ->withErrors($validator)
                ->withInput();
        }

        $pembimbingLama = Pembimbing::find($penugasan_pembimbing->pembimbing_id);
        $pembimbingBaru = Pembimbing::find($request->pembimbing_id);

        // Cek kuota jika pembimbing berubah dan pembimbing baru bukan yang lama
        if ($pembimbingLama->id !== $pembimbingBaru->id && $pembimbingBaru->kuota_bimbingan_aktif >= $pembimbingBaru->maks_kuota_bimbingan) {
            return redirect()->route('admin.penugasan-pembimbing.edit', $penugasan_pembimbing->id)
                ->with('error', 'Kuota bimbingan untuk dosen pembimbing baru ini sudah penuh.')
                ->withInput();
        }

        $statusLama = $penugasan_pembimbing->status_bimbingan;
        $penugasan_pembimbing->update($request->all());
        $statusSekarang = $request->status_bimbingan;

        // Update kuota pembimbing
        if ($pembimbingLama->id !== $pembimbingBaru->id) { // Jika pembimbing diubah
            $pembimbingLama->decrement('kuota_bimbingan_aktif');
            $pembimbingBaru->increment('kuota_bimbingan_aktif');
        } else { // Jika pembimbing tetap sama, cek perubahan status
            if ($statusLama == 'Aktif' && ($statusSekarang == 'Selesai' || $statusSekarang == 'Dibatalkan')) {
                $pembimbingBaru->decrement('kuota_bimbingan_aktif');
            } elseif (($statusLama == 'Selesai' || $statusLama == 'Dibatalkan') && $statusSekarang == 'Aktif') {
                $pembimbingBaru->increment('kuota_bimbingan_aktif');
            }
        }

        return redirect()->route('admin.penugasan-pembimbing.index')->with('success', 'Penugasan pembimbing berhasil diperbarui.');
    }

    public function destroy(BimbinganMagang $penugasan_pembimbing)
    {
        $pembimbing = Pembimbing::find($penugasan_pembimbing->pembimbing_id);
        if ($penugasan_pembimbing->status_bimbingan == 'Aktif' && $pembimbing) {
            $pembimbing->decrement('kuota_bimbingan_aktif');
        }
        $penugasan_pembimbing->delete();

        return redirect()->route('admin.penugasan-pembimbing.index')->with('success', 'Penugasan pembimbing berhasil dihapus.');
    }
}
