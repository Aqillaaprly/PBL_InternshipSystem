<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        UserSeeder::class,
        MahasiswaSeeder::class, // Pastikan ini ada
        PembimbingSeeder::class,
        CompanySeeder::class,
        LowonganSeeder::class,
        PendaftarSeeder::class,
        DokumenPendaftarSeeder::class,
        BimbinganMagangSeeder::class,
        AbsensiMahasiswaSeeder::class,
        ]);
    }
}