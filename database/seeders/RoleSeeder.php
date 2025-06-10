<?php

namespace Database\Seeders; // Ini harus jadi statement pertama setelah <?php

use App\Models\Role;
use Illuminate\Database\Seeder; // Pastikan model Role di-import dengan benar

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'dosen']);
        Role::firstOrCreate(['name' => 'mahasiswa']);
        Role::firstOrCreate(['name' => 'perusahaan']);
    }
}
