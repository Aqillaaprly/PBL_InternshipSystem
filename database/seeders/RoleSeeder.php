<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['role_id' => 1, 'name' => 'admin'],
            ['role_id' => 2, 'name' => 'dosen'],
            ['role_id' => 3, 'name' => 'mahasiswa'],
            ['role_id' => 4, 'name' => 'perusahaan'],
        ]);
    }
}
