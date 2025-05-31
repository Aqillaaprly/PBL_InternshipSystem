<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use Faker\Factory as FakerFactory; // Import Faker

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID'); // Menggunakan Faker untuk data Indonesia
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        if (!$mahasiswaRole) {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. MahasiswaSeeder tidak dapat berjalan.");
            return;
        }

        // Ambil semua user yang memiliki role mahasiswa yang mungkin sudah dibuat oleh UserSeeder
        $usersMahasiswa = User::where('role_id', $mahasiswaRole->id)->get();
        $jumlahUserMahasiswaYangAda = $usersMahasiswa->count();
        $jumlahMahasiswaDibutuhkan = 20;
        $mahasiswaDibuatCount = 0;

        // 1. Buat detail mahasiswa untuk user yang sudah ada
        foreach ($usersMahasiswa as $user) {
            if ($mahasiswaDibuatCount >= $jumlahMahasiswaDibutuhkan) {
                break;
            }

            Mahasiswa::firstOrCreate(
                ['user_id' => $user->id], // Cek berdasarkan user_id
                [
                    'nim' => $user->username, // NIM dari username User
                    'nama' => $user->name,
                    'email' => $user->email,
                    'kelas' => $faker->randomElement(['TI-2A', 'TI-2B', 'TI-2C', 'TI-2D', 'SIB-2A', 'SIB-2B', 'SIB-3A']),
                    'program_studi' => $faker->randomElement(['Teknik Informatika','Sistem Informasi']),
                    'nomor_hp' => $faker->unique()->e164PhoneNumber(), // Format nomor HP internasional
                    'alamat' => $faker->address(),
                ]
            );
            $mahasiswaDibuatCount++;
        }

        // 2. Jika jumlah mahasiswa yang ada kurang dari yang dibutuhkan, buat sisanya menggunakan factory
        $sisaMahasiswaDibutuhkan = $jumlahMahasiswaDibutuhkan - $mahasiswaDibuatCount;

        if ($sisaMahasiswaDibutuhkan > 0) {
            $this->command->info("Membuat {$sisaMahasiswaDibutuhkan} data mahasiswa tambahan (dan user terkait) menggunakan factory...");
            try {
                Mahasiswa::factory()->count($sisaMahasiswaDibutuhkan)->create();
                $mahasiswaDibuatCount += $sisaMahasiswaDibutuhkan;
            } catch (\Exception $e) {
                $this->command->error("Gagal membuat mahasiswa menggunakan factory: " . $e->getMessage());
                // Log errornya jika perlu
                // Log::error("Factory error MahasiswaSeeder: " . $e->getMessage());
            }
        }

        if ($mahasiswaDibuatCount > 0) {
            $this->command->info("Total {$mahasiswaDibuatCount} data detail mahasiswa telah di-seed atau dipastikan ada.");
        } else {
            $this->command->warn("Tidak ada user mahasiswa baru yang perlu di-seed detailnya atau gagal membuat menggunakan factory.");
        }
    }
}