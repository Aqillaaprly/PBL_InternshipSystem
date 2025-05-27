<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Role; // Pastikan model Role ada dan sudah di-seed sebelumnya
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role 'perusahaan'
        $perusahaanRole = Role::where('name', 'perusahaan')->first();

        if (!$perusahaanRole) {
            $this->command->error("Role 'perusahaan' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            // Atau buat role jika tidak ada
            // $perusahaanRole = Role::create(['name' => 'perusahaan']);
            // $this->command->info("Role 'perusahaan' telah dibuat.");
            return;
        }

        // Contoh 1: Membuat perusahaan dengan akun user baru
        $user1 = User::factory()->create([
            'username' => 'pt_abc_jaya',
            'password' => Hash::make('password123'),
            'role_id' => $perusahaanRole->id,
        ]);

        Company::create([
            'user_id' => $user1->id,
            'nama_perusahaan' => 'PT. ABC Jaya',
            'alamat' => 'Jl. Industri No. 123',
            'kota' => 'Jakarta Selatan',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'telepon' => '021-555-1234',
            'email_perusahaan' => 'hrd@abcjaya.co.id',
            'website' => 'https://abcjaya.co.id',
            'deskripsi' => 'Perusahaan terkemuka di bidang teknologi informasi dan konsultasi.',
            'status_kerjasama' => 'Aktif',
        ]);

        // Contoh 2: Membuat perusahaan lain dengan akun user baru
        $user2 = User::factory()->create([
            'username' => 'cv_sinar_maju',
            'password' => Hash::make('password456'),
            'role_id' => $perusahaanRole->id,
        ]);
        Company::create([
            'user_id' => $user2->id,
            'nama_perusahaan' => 'CV. Sinar Maju Bersama',
            'alamat' => 'Jl. Raya Kembangan No. 45',
            'kota' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '60213',
            'telepon' => '031-777-5678',
            'email_perusahaan' => 'info@sinarmaju.com',
            'website' => 'https://sinarmaju.com',
            'deskripsi' => 'Distributor alat berat dan suku cadang.',
            'status_kerjasama' => 'Aktif',
        ]);

        // Contoh 3: Menggunakan factory untuk membuat data perusahaan tambahan
        // Pastikan user dengan role perusahaan sudah ada jika factory Anda memerlukannya,
        // atau modifikasi factory untuk membuat user jika user_id diperlukan.
        // Untuk factory yang tidak secara eksplisit membuat User terkait:
        Company::factory(8)->create();

        // Jika factory Anda membuat User terkait (seperti contoh user_id di CompanyFactory):
        // Company::factory(8)->create()->each(function ($company) use ($perusahaanRole) {
        //     // Jika factory tidak menangani pembuatan User, Anda bisa buat di sini
        //     if (!$company->user_id) {
        //         $user = User::factory()->create([
        //             'username' => strtolower(str_replace([' ', '.'], '_', $company->nama_perusahaan)) . '_user',
        //             'password' => Hash::make('password'),
        //             'role_id' => $perusahaanRole->id,
        //         ]);
        //         $company->user_id = $user->id;
        //         $company->save();
        //     }
        // });

        $this->command->info('Company seeder finished.');
    }
}