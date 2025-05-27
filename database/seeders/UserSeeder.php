<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Import User model
use App\Models\Role; // Import Role model
use Illuminate\Support\Facades\Hash; // Import Hash

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh: Membuat user admin jika belum ada
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'password' => Hash::make('password'), // Ganti dengan password yang aman
                    'role_id' => $adminRole->id,
                ]
            );
        }
        // Tambahkan user lain jika perlu
    }
}