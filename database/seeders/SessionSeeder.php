<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Menggunakan DB facade untuk direct insert
use Illuminate\Support\Str; // Untuk generate random string

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Perhatian: Seeding tabel sessions biasanya tidak diperlukan.
        // Data sesi dikelola secara otomatis oleh Laravel.
        // Contoh ini hanya untuk ilustrasi jika ada kebutuhan yang sangat spesifik.

        // Dapatkan ID pengguna yang ada untuk dikaitkan dengan sesi (misalnya, user dengan ID 1)
        $userId = 1; // Ganti dengan ID pengguna yang valid dari tabel 'users' Anda

        // Ambil pengguna jika ada, untuk validasi (opsional)
        $userExists = DB::table('users')->where('id', $userId)->exists();

        if ($userExists) {
            DB::table('sessions')->insert([
                'id' => Str::random(40), 
                'user_id' => $userId, 
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Contoh Seeder User Agent/1.0',
                'payload' => base64_encode(serialize(['_token' => Str::random(40)])), 
                'last_activity' => time(), 
            ]);

           
            // DB::table('sessions')->insert([
            //     'id' => Str::random(40),
            //     'user_id' => null, // Sesi untuk guest (pengguna belum login)
            //     'ip_address' => '127.0.0.2',
            //     'user_agent' => 'Contoh Seeder User Agent/1.0 Guest',
            //     'payload' => base64_encode(serialize(['_token' => Str::random(40), 'guest_data' => 'some_value'])),
            //     'last_activity' => time() - 3600, // Satu jam yang lalu
            // ]);

        } else {
            
            if (app()->runningInConsole()) {
                $this->command->warn("User dengan ID {$userId} tidak ditemukan. Session seeder untuk pengguna ini dilewati.");
            }
        }
    }
}