<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // Pastikan model Role di-import
use Illuminate\Support\Facades\DB; // Alternatif jika tidak menggunakan model

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cara 1: Menggunakan Model (disarankan jika ada logika tambahan atau event)
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'mahasiswa']);
        Role::create(['name' => 'perusahaan']);

        // Cara 2: Menggunakan DB Facade (lebih direct untuk simple insert)
        // Pastikan untuk menambahkan created_at dan updated_at jika tidak otomatis oleh model
        // DB::table('roles')->insert([
        //     ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
        //     ['name' => 'perusahaan', 'created_at' => now(), 'updated_at' => now()],
        // ]);
    }
}