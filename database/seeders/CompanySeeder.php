<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Faker\Factory as Faker; // Import the Faker factory

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan Faker untuk data Indonesia
        $perusahaanRole = Role::where('name', 'perusahaan')->first();

        if (!$perusahaanRole) {
            $this->command->error("Role 'perusahaan' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan dan role 'perusahaan' ada.");
            Log::error("Role 'perusahaan' tidak ditemukan saat menjalankan CompanySeeder.");
            return;
        }

        $companiesData = [
            [
                'nama_perusahaan' => 'Payfazz',
                'username' => 'payfazz_user',
                'user_email_prefix' => 'contact.payfazz',
                'website' => 'https://fazz.com/about/',
                'logo_path' => 'logos/payfazz_logo.png', // Ganti dengan path lokal jika Anda menyimpannya
                'deskripsi' => 'Financial technology company focusing on payment solutions.',
                'email_perusahaan' => 'official@payfazz.com',
                'alamat' => $faker->address,
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => $faker->postcode,
                'telepon' => $faker->unique()->phoneNumber,
            ],
            [
                'nama_perusahaan' => 'Astra International',
                'username' => 'astra_user',
                'user_email_prefix' => 'hr.astra',
                'website' => 'https://www.astra.co.id',
                'logo_path' => 'logos/astra_logo.png', // Ganti dengan path lokal
                'deskripsi' => 'Indonesian conglomerate operating in various sectors including automotive, financial services, and heavy equipment.',
                'email_perusahaan' => 'recruitment@astra.co.id',
                'alamat' => $faker->address,
                'kota' => 'Jakarta Utara',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => $faker->postcode,
                'telepon' => $faker->unique()->phoneNumber,
            ],
            [
                'nama_perusahaan' => 'Mayora Indah Tbk',
                'username' => 'mayora_user',
                'user_email_prefix' => 'recruitment.mayora',
                'website' => 'https://www.mayoraindah.co.id',
                'logo_path' => 'logos/mayora_logo.png', // Ganti dengan path lokal
                'deskripsi' => 'Indonesian food and beverage company.',
                'email_perusahaan' => 'hr@mayora.co.id',
                'alamat' => $faker->address,
                'kota' => 'Tangerang',
                'provinsi' => 'Banten',
                'kode_pos' => $faker->postcode,
                'telepon' => $faker->unique()->phoneNumber,
            ],
            [
                'nama_perusahaan' => 'Bank Mandiri',
                'username' => 'bank_mandiri_user',
                'user_email_prefix' => 'mandiricare.bankmandiri',
                'website' => 'https://www.bankmandiri.co.id',
                'logo_path' => 'logos/bank_mandiri_logo.png', // Ganti dengan path lokal
                'deskripsi' => 'One of the largest banks in Indonesia.',
                'email_perusahaan' => 'corporate.secretary@bankmandiri.co.id',
                'alamat' => $faker->address,
                'kota' => 'Jakarta Pusat',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => $faker->postcode,
                'telepon' => $faker->unique()->phoneNumber,
            ],
            [
                'nama_perusahaan' => 'Indofood CBP Sukses Makmur Tbk',
                'username' => 'indofood_user',
                'user_email_prefix' => 'recruitment.indofood',
                'website' => 'https://www.indofood.com',
                'logo_path' => 'logos/indofood_logo.png', // Ganti dengan path lokal
                'deskripsi' => 'Major Indonesian food processing company.',
                'email_perusahaan' => 'contact@indofood.co.id',
                'alamat' => $faker->address,
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => $faker->postcode,
                'telepon' => $faker->unique()->phoneNumber,
            ],
             [
                'nama_perusahaan' => 'PT. ABC Jaya',
                'username' => 'pt_abc_jaya',
                'user_email_prefix' => 'user_ptabc',
                'website' => 'https://abcjaya.co.id',
                'logo_path' => 'logos/default_logo.png',
                'deskripsi' => 'Perusahaan terkemuka di bidang teknologi informasi dan konsultasi.',
                'email_perusahaan' => 'hrd@abcjaya.co.id',
                'alamat' => 'Jl. Merdeka No. 10',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40111',
                'telepon' => '022-1234567'
            ],
            [
                'nama_perusahaan' => 'CV. Sinar Maju Bersama',
                'username' => 'cv_sinar_maju',
                'user_email_prefix' => 'user_cvsinar',
                'website' => 'https://sinarmaju.com',
                'logo_path' => 'logos/default_logo.png',
                'deskripsi' => 'Distributor alat berat dan suku cadang.',
                'email_perusahaan' => 'info@sinarmaju.com',
                'alamat' => 'Jl. Pahlawan No. 20',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60111',
                'telepon' => '031-7654321'
            ],
            // Tambahkan data perusahaan lain jika perlu
        ];

        foreach ($companiesData as $companyData) {
            // Membuat atau mencari User
            $user = User::firstOrCreate(
                ['username' => $companyData['username']],
                [
                    'name' => $companyData['nama_perusahaan'] . ' Admin', // atau nama kontak jika ada
                    'email' => $companyData['user_email_prefix'] . '@simmagang.test', // Pastikan email unik
                    'password' => Hash::make('password123'), // Ganti dengan password default yang aman
                    'role_id' => $perusahaanRole->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            // Membuat atau mencari Company, dan mengaitkannya dengan User
            // Menggunakan updateOrCreate untuk memastikan data terisi/diperbarui jika nama perusahaan sudah ada
            Company::updateOrCreate(
                ['nama_perusahaan' => $companyData['nama_perusahaan']], // Kriteria pencarian
                [ // Atribut untuk diisi atau diperbarui
                    'user_id' => $user->id,
                    'alamat' => $companyData['alamat'] ?? $faker->address,
                    'kota' => $companyData['kota'] ?? $faker->city,
                    'provinsi' => $companyData['provinsi'] ?? $faker->state,
                    'kode_pos' => $companyData['kode_pos'] ?? $faker->postcode,
                    'telepon' => $companyData['telepon'] ?? $faker->unique()->phoneNumber,
                    'email_perusahaan' => $companyData['email_perusahaan'], // Ambil dari data eksplisit
                    'website' => $companyData['website'],
                    'deskripsi' => $companyData['deskripsi'],
                    'logo_path' => $companyData['logo_path'], // Pastikan path ini benar relatif ke public/storage/
                    'status_kerjasama' => 'Aktif', // Default ke Aktif
                ]
            );
            $this->command->info("Data untuk {$companyData['nama_perusahaan']} (user dan company) telah di-seed atau dipastikan ada.");
        }

        // Contoh menggunakan factory jika Anda ingin membuat data dummy tambahan
        $numberOfFactoryCompanies = 3; // Jumlah data dummy tambahan yang ingin dibuat
        if ($numberOfFactoryCompanies > 0) {
            $this->command->info("Mencoba membuat {$numberOfFactoryCompanies} data perusahaan tambahan menggunakan factory...");
            try {
                Company::factory()->count($numberOfFactoryCompanies)->create();
                $this->command->info("{$numberOfFactoryCompanies} data perusahaan tambahan berhasil dibuat menggunakan factory.");
            } catch (\Exception $e) {
                $this->command->error("Gagal membuat perusahaan menggunakan factory: " . $e->getMessage());
                Log::error("CompanySeeder Factory Error: " . $e->getMessage());
            }
        }

        $this->command->info('CompanySeeder selesai dijalankan.');
    }
}