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

        if ($adminRole) {
            User::firstOrCreate(
                ['username' => 'rey'], 
                [ 
                    'name' => 'Administrator Sistem',
                    'email' => 'admin@simmagang.test', 
                    'password' => Hash::make('123456'), 
                    'role_id' => $adminRole->id,
                    'email_verified_at' => now(),
                ]
            );
        } else {
            $this->command->error("Role 'admin' tidak ditemukan. User admin tidak di-seed.");
        }

        
        if ($mahasiswaRole) {
            User::firstOrCreate(
                ['username' => '2141720001'], 
                [
                    'name' => 'Budi Mahasiswa',
                    'email' => 'budi.mahasiswa@simmagang.test', 
                    'password' => Hash::make('password123'), 
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            User::firstOrCreate(
                ['username' => '2141720002'], 
                [
                    'name' => 'Siti Pelajar',
                    'email' => 'siti.pelajar@simmagang.test', 
                    'password' => Hash::make('password123'),
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            User::firstOrCreate(
                ['username' => '2141720003'], 
                [
                    'name' => 'Ahmad Cendekia',
                    'email' => 'ahmad.cendekia@simmagang.test', 
                    'password' => Hash::make('password123'),
                    'role_id' => $mahasiswaRole->id,
                    'email_verified_at' => now(),
                ]
            );
            
            $this->command->info(User::where('role_id', $mahasiswaRole->id)->count() . ' user mahasiswa telah di-seed/dipastikan ada.');

        } else {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. User mahasiswa tidak di-seed.");
        }

        
        if ($dosenRole) {
            User::firstOrCreate(
                ['username' => 'dosen001'], 
                [
                    'name' => 'Dr. Retno Pembimbing',
                    'email' => 'retno.pembimbing@simmagang.test', 
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