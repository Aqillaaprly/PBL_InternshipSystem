<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan model User di-import
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pengguna Admin Utama
        User::create([
            'username' => 'rey', // Sesuai dengan username yang Anda gunakan
            'password' => Hash::make('123456'), // Ganti '123456' dengan password yang aman
            'role_id' => 1, // Asumsi role_id 1 adalah untuk 'admin' dari RoleSeeder
            // 'email' => 'admin@example.com', // Tambahkan jika ada kolom email dan diperlukan
            // 'name' => 'Admin User', // Tambahkan jika ada kolom nama dan diperlukan
        ]);

        // Contoh Pengguna Dosen (jika role_id 2 adalah dosen)
        // User::create([
        //     'username' => 'dosen001',
        //     'password' => Hash::make('passworddosen'),
        //     'role_id' => 2,
        //     // 'email' => 'dosen001@example.com',
        //     // 'name' => 'Nama Dosen',
        // ]);

        // Contoh Pengguna Mahasiswa (jika role_id 3 adalah mahasiswa)
        // User::create([
        //     'username' => 'mahasiswa001',
        //     'password' => Hash::make('passwordmahasiswa'),
        //     'role_id' => 3,
        //     // 'email' => 'mahasiswa001@example.com',
        //     // 'name' => 'Nama Mahasiswa',
        // ]);

        // Anda bisa menambahkan lebih banyak pengguna di sini atau menggunakan UserFactory
        // \App\Models\User::factory(10)->create(); // Jika Anda sudah setup UserFactory
    }
}