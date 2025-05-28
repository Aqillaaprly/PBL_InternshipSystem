<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Import Str for random strings if needed for truly unique emails/usernames in factory loop

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perusahaanRole = Role::where('name', 'perusahaan')->first();

        if (!$perusahaanRole) {
            $this->command->error("Role 'perusahaan' tidak ditemukan. Pastikan RoleSeeder sudah dijalankan.");
            Log::error("Role 'perusahaan' tidak ditemukan saat menjalankan CompanySeeder.");
            return;
        }

        $placeholderLogo = 'logos/default_logo.png';
        if (!Storage::disk('public')->exists($placeholderLogo)) {
            $this->command->warn("Placeholder logo '{$placeholderLogo}' not found in storage/app/public/. Seeding with path name only. Please create the placeholder file for display.");
            // Optionally, create a dummy placeholder if it doesn't exist to avoid issues in development
            // Storage::disk('public')->makeDirectory('logos');
            // Storage::disk('public')->put($placeholderLogo, 'Placeholder image content');
        }

        // Contoh 1: PT. ABC Jaya
        $user1 = User::firstOrCreate(
            ['username' => 'pt_abc_jaya'],
            [
                'name' => 'PT. ABC Jaya Admin',
                'email' => 'user_ptabc@example.com', // Ensure this email is unique if you run this multiple times and 'admin' user has it
                'password' => Hash::make('password123'),
                'role_id' => $perusahaanRole->id,
                'email_verified_at' => now(), // Good to have for factory-created users
                'remember_token' => Str::random(10),
            ]
        );

        Company::firstOrCreate(
            ['nama_perusahaan' => 'PT. ABC Jaya'],
            [
                'user_id' => $user1->id,
                'alamat' => 'Jl. Industri No. 123',
                'kota' => 'Jakarta Selatan',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12345',
                'telepon' => '021-555-1234',
                'email_perusahaan' => 'hrd@abcjaya.co.id',
                'website' => 'https://abcjaya.co.id',
                'deskripsi' => 'Perusahaan terkemuka di bidang teknologi informasi dan konsultasi.',
                'logo_path' => $placeholderLogo,
                'status_kerjasama' => 'Aktif',
            ]
        );
        $this->command->info('PT. ABC Jaya user and company seeded/ensured.');

        // Contoh 2: CV. Sinar Maju Bersama
        $user2 = User::firstOrCreate(
            ['username' => 'cv_sinar_maju'],
            [
                'name' => 'CV. Sinar Maju Admin',
                'email' => 'user_cvsinar@example.com', // Ensure this email is unique
                'password' => Hash::make('password456'),
                'role_id' => $perusahaanRole->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        Company::firstOrCreate(
            ['nama_perusahaan' => 'CV. Sinar Maju Bersama'],
            [
                'user_id' => $user2->id,
                'alamat' => 'Jl. Raya Kembangan No. 45',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '60213',
                'telepon' => '031-777-5678',
                'email_perusahaan' => 'info@sinarmaju.com',
                'website' => 'https://sinarmaju.com',
                'deskripsi' => 'Distributor alat berat dan suku cadang.',
                'logo_path' => $placeholderLogo,
                'status_kerjasama' => 'Aktif',
            ]
        );
        $this->command->info('CV. Sinar Maju Bersama user and company seeded/ensured.');

        // Contoh 3: Menggunakan factory untuk membuat data perusahaan tambahan
        $numberOfFactoryCompanies = 8;
        $this->command->info("Attempting to seed {$numberOfFactoryCompanies} additional companies using factory...");
        for ($i = 0; $i < $numberOfFactoryCompanies; $i++) {
            // For factory-created users, ensure uniqueness, UserFactory should handle this if using unique()
            // Or use firstOrCreate here as well if you have predictable usernames/emails
            $uniqueUsername = 'factory_company_user_' . Str::random(5) . ($i + 1); // More unique username
            $uniqueEmail = 'factory_user_'. Str::random(5) . ($i+1).'@example.com';

            $factoryUser = User::firstOrCreate(
                ['username' => $uniqueUsername],
                [
                    'name' => 'Factory Company User ' . ($i + 1),
                    'email' => $uniqueEmail,
                    'password' => Hash::make('password'),
                    'role_id' => $perusahaanRole->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            // CompanyFactory should also provide a unique name or use firstOrCreate
             Company::factory()->create([
                'user_id' => $factoryUser->id,
                'nama_perusahaan' => 'Factory Company ' . Str::random(5) . ($i + 1) // Ensure unique name
            ]);
        }
        $this->command->info("{$numberOfFactoryCompanies} additional companies seeded using factory.");

        $this->command->info('Company seeder finished successfully.');
    }
}