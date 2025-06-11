<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing; // Import model Pembimbing
use App\Models\Role; // Import model Role
use App\Models\User; // Import model User
use App\Models\Mahasiswa; // Import model Mahasiswa
use Illuminate\Http\Request; // Import kelas Request
use Illuminate\Support\Facades\Hash; // Import facade Hash untuk hashing password
use Illuminate\Support\Facades\Validator; // Import facade Validator untuk validasi data
use Illuminate\Validation\Rule; // Import kelas Rule untuk aturan validasi lanjutan
use Illuminate\Support\Facades\DB; // Tambahkan import untuk facade DB
use Illuminate\Support\Facades\Log; // Tambahkan import untuk facade Log

class PembimbingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar sumber daya (pembimbing).
     */
    public function index(Request $request)
   {
        $query = Pembimbing::with('user')->orderBy('nama_lengkap', 'asc');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nip', 'like', "%{$searchTerm}%")
                    ->orWhere('nama_lengkap', 'like', "%{$searchTerm}%")
                    ->orWhere('email_institusi', 'like', "%{$searchTerm}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('username', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%");
                    });
            });
        }
        $pembimbings = $query->paginate(10)->withQueryString();


        return view('admin.Pembimbing.index', compact('pembimbings'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan formulir untuk membuat sumber daya baru (pembimbing).
     */
    public function create()
    {
        // Mengembalikan tampilan (view) untuk membuat pembimbing baru.
        // Asumsi file create.blade.php berada di resources/views/admin/Pembimbing/create.blade.php
        return view('admin.Pembimbing.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan sumber daya (pembimbing) yang baru dibuat ke dalam penyimpanan (database).
     */
    public function store(Request $request)
    {
        // Validasi data permintaan yang masuk.
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:255|unique:users,username|unique:pembimbings,nip', // NIP digunakan sebagai username untuk User dan harus unik di tabel Pembimbing
            'email_login' => 'required|string|email|max:255|unique:users,email', // Email untuk login User
            'email_institusi' => 'required|string|email|max:255|unique:pembimbings,email_institusi', // Email institusi untuk Pembimbing
            'password' => 'required|string|min:6|confirmed',
            'nomor_telepon' => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:255',
            'program_studi_homebase' => 'nullable|string|max:255',
            'bidang_keahlian_utama' => 'nullable|string',
            'maks_kuota_bimbingan' => 'required|integer|min:0',
            'status_aktif' => 'required|boolean',
        ]);

        // Jika validasi gagal, arahkan kembali dengan kesalahan dan input sebelumnya.
        if ($validator->fails()) {
            return redirect()->route('admin.pembimbings.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Dapatkan peran 'dosen'. Jika tidak ditemukan, arahkan kembali dengan kesalahan.
        $dosenRole = Role::where('name', 'dosen')->first();
        if (!$dosenRole) {
            return redirect()->route('admin.pembimbings.create')->with('error', 'Role dosen tidak ditemukan.')->withInput();
        }

        try {
            DB::beginTransaction(); // Memulai transaksi database

            // Buat catatan User baru untuk pembimbing.
            $user = User::create([
                'name' => $request->nama_lengkap,
                'username' => $request->nip, // NIP berfungsi sebagai username
                'email' => $request->email_login,
                'password' => Hash::make($request->password),
                'role_id' => $dosenRole->id,
                'email_verified_at' => now(), // Set email_verified_at ke waktu saat ini
            ]);

            // Buat catatan detail Pembimbing baru.
            Pembimbing::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama_lengkap' => $request->nama_lengkap,
                'email_institusi' => $request->email_institusi,
                'nomor_telepon' => $request->nomor_telepon,
                'jabatan_fungsional' => $request->jabatan_fungsional,
                'program_studi_homebase' => $request->program_studi_homebase,
                'bidang_keahlian_utama' => $request->bidang_keahlian_utama,
                'maks_kuota_bimbingan' => $request->maks_kuota_bimbingan,
                'kuota_aktif' => 0, // Initialize active quota to 0 when creating a new pembimbing
                'status_aktif' => $request->status_aktif,
            ]);

            DB::commit(); // Komit transaksi jika berhasil
            // Arahkan ke halaman indeks dengan pesan sukses.
            return redirect()->route('admin.pembimbings.index')->with('success', 'Data pembimbing berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Gagal menambahkan pembimbing: ' . $e->getMessage(), ['exception' => $e]); // Catat kesalahan
            return redirect()->route('admin.pembimbings.create')->with('error', 'Terjadi kesalahan saat menambahkan pembimbing. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     * Menampilkan sumber daya yang ditentukan.
     * Parameter $pembimbing adalah instance dari User (melalui Route Model Binding).
     */
   public function show(Pembimbing $pembimbing)
    {
        // Eager load 'user' for the pembimbing itself,
        // and 'bimbinganMagangs' with nested 'mahasiswa' (which is a User model)
        // and 'detailMahasiswa' (the Mahasiswa profile data), and 'company)
        $pembimbing->load([
            'user',
            'bimbinganMagangs' => function ($query) {
                $query->with(['mahasiswa.detailMahasiswa', 'company']);
            }
        ]);
        return view('admin.Pembimbing.show', compact('pembimbing'));
    }

    public function edit(Pembimbing $pembimbing)
    {
        $pembimbing->load('user');

        // Fetch all students (mahasiswa users) with their detailMahasiswa to populate the dropdown
        $mahasiswaUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'mahasiswa');
        })->with('detailMahasiswa')->get();

        // Load bimbinganMagangs for this pembimbing to display and manage them
        $pembimbing->load([
            'bimbinganMagangs' => function ($query) {
                $query->with(['mahasiswa.detailMahasiswa', 'company']);
            }
        ]);


        return view('admin.Pembimbing.edit', compact('pembimbing', 'mahasiswaUsers')); // Pass mahasiswaUsers to the view
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui sumber daya yang ditentukan di penyimpanan (database).
     * Parameter $pembimbing adalah instance dari User (melalui Route Model Binding).
     */
    public function update(Request $request, Pembimbing $pembimbing) // Changed type hint from User to Pembimbing
     {
        $user = $pembimbing->user; // Access the related user model

        $validator = Validator::make($request->all(), [
            'username_login' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id ?? null)],
            'email_login' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id ?? null)],
            'password' => 'nullable|string|min:6|confirmed',
            'nip' => ['required', 'string', 'max:255', Rule::unique('pembimbings', 'nip')->ignore($pembimbing->id)],
            'nama_lengkap' => 'required|string|max:255',
            'email_institusi' => ['required', 'string', 'email', 'max:255', Rule::unique('pembimbings', 'email_institusi')->ignore($pembimbing->id)],
            'nomor_telepon' => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:255',
            'program_studi_homebase' => 'nullable|string|max:255',
            'bidang_keahlian_utama' => 'nullable|string',
            'maks_kuota_bimbingan' => 'required|integer|min:0',
            'status_aktif' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pembimbings.edit', $pembimbing->id)
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            if ($user) {
                $userData = [
                    'name' => $request->nama_lengkap,
                    'username' => $request->username_login,
                    'email' => $request->email_login,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
            } else {
                // Jika user tidak ada (seharusnya tidak terjadi jika store benar), buat user baru
                $dosenRole = Role::where('name', 'dosen')->firstOrFail();
                $user = User::create([
                    'name' => $request->nama_lengkap,
                    'username' => $request->username_login,
                    'email' => $request->email_login,
                    'password' => Hash::make($request->password ?: 'password'), // Default password jika tidak diisi
                    'role_id' => $dosenRole->id,
                    'email_verified_at' => now(),
                ]);
                $pembimbing->user_id = $user->id; // Tautkan user baru
            }

            $pembimbingDataToUpdate = $request->only([
                'nip', 'nama_lengkap', 'email_institusi', 'nomor_telepon',
                'jabatan_fungsional', 'program_studi_homebase',
                'bidang_keahlian_utama', 'maks_kuota_bimbingan', 'status_aktif',
            ]);
            // Pastikan user_id juga diupdate jika baru dibuat di atas
            if (! $pembimbing->user_id && $user) {
                $pembimbingDataToUpdate['user_id'] = $user->id;
            }

            $pembimbing->update($pembimbingDataToUpdate);

            DB::commit();

            return redirect()->route('admin.pembimbings.index')->with('success', 'Data pembimbing berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pembimbing: '.$e->getMessage());

            return redirect()->route('admin.pembimbings.edit', $pembimbing->id)
                ->with('error', 'Gagal memperbarui pembimbing: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus sumber daya yang ditentukan dari penyimpanan.
     * Parameter $pembimbing adalah instance dari User (melalui Route Model Binding).
     */
    public function destroy(User $pembimbing)
    {
        // Cegah penghapusan user non-dosen melalui endpoint ini.
        if (!$pembimbing->role || $pembimbing->role->name !== 'dosen') {
            abort(403, 'Tidak diizinkan menghapus user yang bukan pembimbing melalui endpoint ini.');
        }

        try {
            DB::beginTransaction(); // Memulai transaksi database

            // Hapus catatan User. Ini akan memicu penghapusan berjenjang untuk detail Pembimbing terkait jika dikonfigurasi.
            $pembimbing->delete();

            DB::commit(); // Komit transaksi jika berhasil
            // Arahkan ke halaman indeks dengan pesan sukses.
            return redirect()->route('admin.pembimbings.index')->with('success', 'Pembimbing berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi kesalahan
            Log::error('Gagal menghapus pembimbing: ' . $e->getMessage(), ['exception' => $e]); // Catat kesalahan
            return redirect()->route('admin.pembimbings.index')->with('error', 'Terjadi kesalahan saat menghapus pembimbing. Silakan coba lagi.');
        }
    }
}
