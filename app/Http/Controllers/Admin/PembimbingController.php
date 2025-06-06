<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Pastikan ini ada

class PembimbingController extends Controller
{
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

    public function create()
    {
        return view('admin.Pembimbing.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username_login' => 'required|string|max:255|unique:users,username',
            'email_login' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nip' => 'required|string|max:255|unique:pembimbings,nip',
            'nama_lengkap' => 'required|string|max:255',
            'email_institusi' => 'required|string|email|max:255|unique:pembimbings,email_institusi',
            'nomor_telepon' => 'nullable|string|max:20',
            'jabatan_fungsional' => 'nullable|string|max:255',
            'program_studi_homebase' => 'nullable|string|max:255',
            'bidang_keahlian_utama' => 'nullable|string',
            'maks_kuota_bimbingan' => 'required|integer|min:0',
            'status_aktif' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.pembimbings.create')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $dosenRole = Role::where('name', 'dosen')->firstOrFail();

            $user = User::create([
                'name' => $request->nama_lengkap,
                'username' => $request->username_login,
                'email' => $request->email_login,
                'password' => Hash::make($request->password),
                'role_id' => $dosenRole->id,
                'email_verified_at' => now(),
            ]);

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
                'status_aktif' => $request->status_aktif,
                'kuota_bimbingan_aktif' => 0,
            ]);

            DB::commit();

            return redirect()->route('admin.pembimbings.index')->with('success', 'Pembimbing berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating pembimbing: '.$e->getMessage());

            return redirect()->route('admin.pembimbings.create')
                ->with('error', 'Gagal menambahkan pembimbing: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show(Pembimbing $pembimbing)
    {
        $pembimbing->load('user');

        return view('admin.Pembimbing.show', compact('pembimbing'));
    }

    public function edit(Pembimbing $pembimbing)
    {
        $pembimbing->load('user');

        return view('admin.Pembimbing.edit', compact('pembimbing'));
    }

    public function update(Request $request, Pembimbing $pembimbing)
    {
        $user = $pembimbing->user;

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

    public function destroy(Pembimbing $pembimbing)
    {
        DB::beginTransaction();
        try {
            if ($pembimbing->bimbinganMagangs()->where('status_bimbingan', 'Aktif')->exists()) {
                return redirect()->route('admin.pembimbings.index')->with('error', 'Tidak dapat menghapus pembimbing yang masih memiliki mahasiswa bimbingan aktif.');
            }

            $user = $pembimbing->user;
            $pembimbing->delete();

            if ($user) {
                // Periksa apakah user ini hanya memiliki peran sebagai dosen pembimbing yang dihapus
                // Ini adalah asumsi sederhana, jika user bisa punya banyak peran, logikanya perlu lebih kompleks
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.pembimbings.index')->with('success', 'Pembimbing dan akun login terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting pembimbing: '.$e->getMessage());

            return redirect()->route('admin.pembimbings.index')->with('error', 'Gagal menghapus pembimbing.');
        }
    }
}
