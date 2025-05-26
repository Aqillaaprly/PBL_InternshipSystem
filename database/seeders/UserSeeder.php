<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'student1',
                'email' => 'student1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'supervisor1',
                'email' => 'supervisor1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'supervisor',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'company1',
                'email' => 'company1@example.com',
                'password' => Hash::make('password123'),
                'role' => 'company',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'admin1',
                'email' => 'admin1@example.com',
                'password' => Hash::make('adminpass'),
                'role' => 'admin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
