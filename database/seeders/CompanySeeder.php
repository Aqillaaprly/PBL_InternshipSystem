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
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Create an instance of Faker, optionally specify locale e.g., 'id_ID' for Indonesian data
        $perusahaanRole = Role::where('name', 'perusahaan')->first();

        if (!$perusahaanRole) {
            $this->command->error("Role 'perusahaan' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            Log::error("Role 'perusahaan' tidak ditemukan saat menjalankan CompanySeeder.");
            return;
        }

        $companiesData = [
            [
                'nama_perusahaan' => 'Payfazz',
                'username' => 'payfazz_user',
                'user_email_prefix' => 'contact.payfazz', // Using a prefix for email generation
                'website' => 'https://fazz.com/about/',
                'logo_path' => 'https://logovectorseek.com/wp-content/uploads/2020/10/payfazz-logo-vector.png',
                'deskripsi' => 'Financial technology company focusing on payment solutions.',
                'email_perusahaan' => 'official@payfazz.com'
            ],
            [
                'nama_perusahaan' => 'Astra International',
                'username' => 'astra_user',
                'user_email_prefix' => 'hr.astra',
                'website' => 'https://www.astra.co.id',
                'logo_path' => 'https://images.seeklogo.com/logo-png/31/1/astra-international-logo-png_seeklogo-319240.png',
                'deskripsi' => 'Indonesian conglomerate operating in various sectors including automotive, financial services, and heavy equipment.',
                'email_perusahaan' => 'recruitment@astra.co.id'
            ],
            [
                'nama_perusahaan' => 'Mayora Indah Tbk',
                'username' => 'mayora_user',
                'user_email_prefix' => 'recruitment.mayora',
                'website' => 'https://www.mayoraindah.co.id',
                'logo_path' => 'https://images.seeklogo.com/logo-png/20/1/mayora-logo-png_seeklogo-203730.png',
                'deskripsi' => 'Indonesian food and beverage company.',
                'email_perusahaan' => 'hr@mayora.co.id'
            ],
            [
                'nama_perusahaan' => 'Bank Mandiri',
                'username' => 'bank_mandiri_user',
                'user_email_prefix' => 'mandiricare.bankmandiri',
                'website' => 'https://www.bankmandiri.co.id',
                'logo_path' => 'https://bankmandiri.co.id/favicon.ico',
                'deskripsi' => 'One of the largest banks in Indonesia.',
                'email_perusahaan' => 'corporate.secretary@bankmandiri.co.id'
            ],
            [
                'nama_perusahaan' => 'Indofood CBP Sukses Makmur Tbk',
                'username' => 'indofood_user',
                'user_email_prefix' => 'recruitment.indofood',
                'website' => 'https://www.indofood.com',
                'logo_path' => 'https://images.seeklogo.com/logo-png/29/1/indofood-logo-png_seeklogo-298884.png',
                'deskripsi' => 'Major Indonesian food processing company.',
                'email_perusahaan' => 'contact@indofood.co.id'
            ],
            [
                'nama_perusahaan' => 'Paragon Technology and Innovation',
                'username' => 'paragon_user',
                'user_email_prefix' => 'hr.paragon',
                'website' => 'https://www.paragon-innovation.com',
                'logo_path' => 'https://images.seeklogo.com/logo-png/24/1/paragon-logo-png_seeklogo-247277.png',
                'deskripsi' => 'Indonesian beauty company.',
                'email_perusahaan' => 'info@paragon-innovation.com'
            ],
            [
                'nama_perusahaan' => 'Kawan Lama Sejahtera',
                'username' => 'kawan_lama_user',
                'user_email_prefix' => 'customercare.kawanlama',
                'website' => 'https://www.kawanlama.com',
                'logo_path' => 'https://kawanlama.com/favicon.ico',
                'deskripsi' => 'Indonesian retail group focusing on home improvement, lifestyle, and furnishings.',
                'email_perusahaan' => 'info@kawanlamagroup.com'
            ],
            [
                'nama_perusahaan' => 'Indomaret',
                'username' => 'indomaret_user',
                'user_email_prefix' => 'kontak.indomaret',
                'website' => 'https://www.indomaret.co.id',
                'logo_path' => 'https://images.seeklogo.com/logo-png/50/1/indomaret-logo-png_seeklogo-504056.png',
                'deskripsi' => 'Indonesian chain of convenience stores.',
                'email_perusahaan' => 'help@indomaret.co.id'
            ],
            [
                'nama_perusahaan' => 'PT. ABC Jaya',
                'username' => 'pt_abc_jaya',
                'user_email_prefix' => 'user_ptabc',
                'website' => 'https://abcjaya.co.id',
                'logo_path' => 'logos/default_logo.png', // Placeholder if no specific logo
                'deskripsi' => 'Perusahaan terkemuka di bidang teknologi informasi dan konsultasi.',
                'email_perusahaan' => 'hrd@abcjaya.co.id'
            ],
            [
                'nama_perusahaan' => 'CV. Sinar Maju Bersama',
                'username' => 'cv_sinar_maju',
                'user_email_prefix' => 'user_cvsinar',
                'website' => 'https://sinarmaju.com',
                'logo_path' => 'logos/default_logo.png', // Placeholder
                'deskripsi' => 'Distributor alat berat dan suku cadang.',
                'email_perusahaan' => 'info@sinarmaju.com'
            ],
        ];

        foreach ($companiesData as $companyData) {
            $user = User::firstOrCreate(
                ['username' => $companyData['username']],
                [
                    'name' => $companyData['nama_perusahaan'] . ' Admin',
                    'email' => $companyData['user_email_prefix'] . '@example.com', // Generates unique email
                    'password' => Hash::make('password123'),
                    'role_id' => $perusahaanRole->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            Company::firstOrCreate(
                ['nama_perusahaan' => $companyData['nama_perusahaan']],
                [
                    'user_id' => $user->id,
                    'alamat' => $companyData['alamat'] ?? $faker->address, // Use $faker instance
                    'kota' => $companyData['kota'] ?? $faker->city,       // Use $faker instance
                    'provinsi' => $companyData['provinsi'] ?? $faker->state, // Use $faker instance
                    'kode_pos' => $companyData['kode_pos'] ?? $faker->postcode, // Use $faker instance
                    'telepon' => $companyData['telepon'] ?? $faker->unique()->phoneNumber, // Use $faker instance
                    'email_perusahaan' => $companyData['email_perusahaan'] ?? $user->email,
                    'website' => $companyData['website'],
                    'deskripsi' => $companyData['deskripsi'],
                    'logo_path' => $companyData['logo_path'],
                    'status_kerjasama' => 'Aktif',
                ]
            );
            $this->command->info($companyData['nama_perusahaan'] . ' user and company seeded/ensured.');
        }

        // Factory for additional generic companies if needed
        $numberOfFactoryCompanies = 0; // Set to 0 if the above list is comprehensive enough for now
        if ($numberOfFactoryCompanies > 0) {
            $this->command->info("Attempting to seed {$numberOfFactoryCompanies} additional companies using factory...");
            for ($i = 0; $i < $numberOfFactoryCompanies; $i++) {
                $uniqueUsername = 'factory_comp_user_' . Str::random(5) . ($i + 1);
                $uniqueEmail = 'factory_comp_email_'. Str::random(5) . ($i+1).'@example.com';

                $factoryUser = User::firstOrCreate(
                    ['username' => $uniqueUsername],
                    [
                        'name' => 'Factory Co User ' . ($i + 1),
                        'email' => $uniqueEmail,
                        'password' => Hash::make('password'),
                        'role_id' => $perusahaanRole->id,
                        'email_verified_at' => now(),
                        'remember_token' => Str::random(10),
                    ]
                );
                
                Company::factory()->create([
                    'user_id' => $factoryUser->id,
                    'nama_perusahaan' => 'Factory Company ' . Str::random(5) . ($i + 1)
                ]);
            }
            $this->command->info("{$numberOfFactoryCompanies} additional companies seeded using factory.");
        }

        $this->command->info('Company seeder finished successfully.');
    }
}