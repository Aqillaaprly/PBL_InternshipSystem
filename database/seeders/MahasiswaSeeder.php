<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Untuk password default jika membuat User baru

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = FakerFactory::create('id_ID'); // Menggunakan Faker untuk data Indonesia
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();

        if (! $mahasiswaRole) {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. MahasiswaSeeder tidak dapat berjalan.");
            return;
        }

        $jumlahMahasiswaDibutuhkan = 20;
        $mahasiswaDibuatCount = 0;

        // 1. Proses User yang sudah ada dengan role 'mahasiswa' dan buat detail Mahasiswa untuk mereka
        $usersMahasiswaYangAda = User::where('role_id', $mahasiswaRole->id)
                                    ->doesntHave('detailMahasiswa') // Hanya proses user yang belum punya detail mahasiswa
                                    ->get();

        foreach ($usersMahasiswaYangAda as $user) {
            if ($mahasiswaDibuatCount >= $jumlahMahasiswaDibutuhkan) {
                break;
            }

            // Memastikan data NIM/Nama dari User terisi (jika tidak dari UserSeeder)
            $nim = $user->username ?? 'NIM' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $nama = $user->name ?? $faker->name; // Gunakan faker jika nama user kosong

            Mahasiswa::firstOrCreate(
                ['user_id' => $user->id], // Kriteria unik untuk firstOrCreate
                [
                    'nim' => $nim,
                    'nama' => $nama,
                     'email' => $user->email,
                    'program_studi' => $faker->randomElement(['Teknik Informatika', 'Sistem Informasi']),
                    'kelas' => $faker->randomElement(['TI-2A', 'TI-4B', 'TI-2C', 'TI-2D', 'SIB-2A', 'SIB-2B', 'SIB-3A']),
                    'nomor_hp' => $faker->unique()->e164PhoneNumber(),
                    'alamat' => $faker->address(),
                    
                    
                ]
            );
            $mahasiswaDibuatCount++;
        }

        // 2. Jika jumlah mahasiswa yang ada (dengan detail lengkap) kurang dari yang dibutuhkan, buat sisanya
        $mahasiswaDenganDetailLengkap = Mahasiswa::count(); // Hitung total mahasiswa yang punya detail
        $sisaMahasiswaDibutuhkan = $jumlahMahasiswaDibutuhkan - $mahasiswaDenganDetailLengkap;

        if ($sisaMahasiswaDibutuhkan > 0) {
            $this->command->info("Membuat {$sisaMahasiswaDibutuhkan} data mahasiswa tambahan (dan user terkait) menggunakan factory...");
            try {
                // Gunakan MahasiswaFactory untuk membuat Mahasiswa, yang harusnya juga membuat User terkait
                Mahasiswa::factory()->count($sisaMahasiswaDibutuhkan)->create();
                $mahasiswaDibuatCount += $sisaMahasiswaDibutuhkan;
            } catch (\Exception $e) {
                $this->command->error('Gagal membuat mahasiswa menggunakan factory: '.$e->getMessage());
            }
        }

        if ($mahasiswaDibuatCount > 0) {
            $this->command->info("Total {$mahasiswaDibuatCount} data detail mahasiswa telah di-seed atau dipastikan ada.");
        } else {
            $this->command->warn('Tidak ada user mahasiswa baru yang perlu di-seed detailnya atau gagal membuat menggunakan factory.');
        }
    }
}
