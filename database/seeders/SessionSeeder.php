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
                'id' => Str::random(40), // ID sesi acak khas Laravel
                'user_id' => $userId, // Kaitkan dengan user_id yang ada
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Contoh Seeder User Agent/1.0',
                // 'payload' biasanya berisi data sesi yang ter-serialize dan ter-encode.
                // Membuat payload yang valid secara manual itu rumit.
                // Untuk contoh ini, kita akan gunakan string kosong atau data dummy sederhana yang di-encode.
                // Payload yang sebenarnya dibuat oleh Laravel saat session disimpan.
                'payload' => base64_encode(serialize(['_token' => Str::random(40)])), // Contoh payload minimal
                'last_activity' => time(), // Waktu aktivitas terakhir (timestamp Unix)
            ]);

            // Anda bisa menambahkan lebih banyak data sesi jika perlu
            // DB::table('sessions')->insert([
            //     'id' => Str::random(40),
            //     'user_id' => null, // Sesi untuk guest (pengguna belum login)
            //     'ip_address' => '127.0.0.2',
            //     'user_agent' => 'Contoh Seeder User Agent/1.0 Guest',
            //     'payload' => base64_encode(serialize(['_token' => Str::random(40), 'guest_data' => 'some_value'])),
            //     'last_activity' => time() - 3600, // Satu jam yang lalu
            // ]);

        } else {
            // Berikan output jika user_id tidak ditemukan, agar seeder tidak gagal diam-diam
            // atau lempar exception jika ini adalah kondisi kritis.
            if (app()->runningInConsole()) {
                $this->command->warn("User dengan ID {$userId} tidak ditemukan. Session seeder untuk pengguna ini dilewati.");
            }
        }
    }
}