<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $dosenRole = Role::where('name', 'dosen')->first();
        // $perusahaanRole = Role::where('name', 'perusahaan')->first(); // Anda sudah punya CompanySeeder untuk ini

        // Membuat Admin
        if ($adminRole) {
            User::firstOrCreate(
                ['username' => 'admin'], // Cari berdasarkan username
                [ // Jika tidak ditemukan, buat dengan atribut ini
                    'name' => 'Administrator Sistem',
                    'email' => 'admin@simmagang.test', // Pastikan email unik jika tidak nullable
                    'password' => Hash::make('password'), // Ganti dengan password yang aman
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        } else {
            $this->command->error("Role 'admin' tidak ditemukan. User admin tidak di-seed.");
        }

        // Membuat Contoh Mahasiswa
        if ($mahasiswaRole) {
            User::firstOrCreate(
                ['username' => '2141720001'], // NIM sebagai username
                [
                    'name' => 'Budi Mahasiswa',
                    'email' => 'budi.mahasiswa@simmagang.test', // Email unik
                    'password' => Hash::make('password123'), // Ganti password jika perlu
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            User::firstOrCreate(
                ['username' => '2141720002'], // NIM sebagai username
                [
                    'name' => 'Siti Pelajar',
                    'email' => 'siti.pelajar@simmagang.test', // Email unik
                    'password' => Hash::make('password123'),
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            User::firstOrCreate(
                ['username' => '2141720003'], // NIM sebagai username
                [
                    'name' => 'Ahmad Cendekia',
                    'email' => 'ahmad.cendekia@simmagang.test', // Email unik
                    'password' => Hash::make('password123'),
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            // Tambahkan lebih banyak mahasiswa jika perlu
            $this->command->info(User::where('role_id', $mahasiswaRole->id)->count() . ' user mahasiswa telah di-seed/dipastikan ada.');

        } else {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. User mahasiswa tidak di-seed.");
        }

        // Membuat Contoh Dosen
        if ($dosenRole) {
            User::firstOrCreate(
                ['username' => 'dosen001'], // Contoh NIDN atau username unik dosen
                [
                    'name' => 'Dr. Retno Pembimbing',
                    'email' => 'retno.pembimbing@simmagang.test', // Email unik
                    'password' => Hash::make('password'),
                    'role_id' => $dosenRole->id,
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info(User::where('role_id', $dosenRole->id)->count() . ' user dosen telah di-seed/dipastikan ada.');

        } else {
            $this->command->error("Role 'dosen' tidak ditemukan. User dosen tidak di-seed.");
        }
    }
}