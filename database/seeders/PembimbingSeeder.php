<?php

namespace Database\Seeders;

use App\Models\Pembimbing;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PembimbingSeeder extends Seeder
{
    public function run(): void
    {
        $dosenRole = Role::where('name', 'dosen')->first();

        if (! $dosenRole) {
            $this->command->error("Role 'dosen' tidak ditemukan. PembimbingSeeder tidak dapat membuat data pembimbing terkait user.");

            // Alternatif: buat pembimbing tanpa user_id jika skema memperbolehkan
            // Pembimbing::factory()->count(15)->create(['user_id' => null]); // Diubah ke 15 jika tanpa user
            // $this->command->info("15 data pembimbing (tanpa user terkait) telah di-seed menggunakan factory.");
            return;
        }

        // Ambil user dengan role dosen yang sudah dibuat oleh UserSeeder
        $usersDosen = User::where('role_id', $dosenRole->id)->get();
        $jumlahPembimbingDibuat = 0;
        $targetPembimbing = 15; // Jumlah pembimbing yang ingin dibuat diubah menjadi 15

        foreach ($usersDosen as $user) {
            if ($jumlahPembimbingDibuat >= $targetPembimbing) {
                break;
            }

            // Cek apakah sudah ada detail pembimbing untuk user ini
            if (! Pembimbing::where('user_id', $user->id)->exists()) {
                Pembimbing::factory()->create([
                    'user_id' => $user->id,
                    'nip' => $user->username, // Asumsi username user dosen adalah NIP
                    'nama_lengkap' => $user->name,
                    'email_institusi' => $user->email,
                    // Factory akan mengisi sisa field dengan data dummy,
                    // termasuk program_studi_homebase sesuai update di factory
                ]);
                $jumlahPembimbingDibuat++;
            }
        }

        // Jika user dosen yang ada kurang dari target, buat sisanya dengan factory
        // (factory Pembimbing juga akan membuat User baru jika dikonfigurasi demikian)
        $sisaDibutuhkan = $targetPembimbing - $jumlahPembimbingDibuat;
        if ($sisaDibutuhkan > 0) {
            $this->command->info("Membuat {$sisaDibutuhkan} data pembimbing tambahan (dan user terkait jika factory diatur) menggunakan factory...");
            try {
                Pembimbing::factory()->count($sisaDibutuhkan)->create(); // Ini akan menggunakan factory yang sudah diupdate
                $jumlahPembimbingDibuat += $sisaDibutuhkan;
            } catch (\Exception $e) {
                $this->command->error('Gagal membuat pembimbing menggunakan factory: '.$e->getMessage());
            }
        }

        if ($jumlahPembimbingDibuat > 0) {
            $this->command->info("Total {$jumlahPembimbingDibuat} data pembimbing telah di-seed.");
        } else {
            $this->command->warn('Tidak ada data pembimbing baru yang di-seed (mungkin sudah ada atau tidak ada user dosen yang cukup).');
        }
    }
}
