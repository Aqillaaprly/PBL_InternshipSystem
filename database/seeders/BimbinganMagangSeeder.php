<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BimbinganMagang;
use App\Models\User;
use App\Models\Pembimbing;
use App\Models\Company;
use App\Models\Lowongan;

class BimbinganMagangSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaUsers = User::whereHas('role', function($q) {
            $q->where('name', 'mahasiswa');
        })->get();

        $pembimbing = Pembimbing::first(); // Ambil 1 pembimbing pertama
        $company = Company::first();
        $lowongan = Lowongan::first();

        foreach ($mahasiswaUsers as $mahasiswa) {
            BimbinganMagang::firstOrCreate(
                [
                    'mahasiswa_user_id' => $mahasiswa->id,
                    'pembimbing_id' => $pembimbing->id,
                    'periode_magang' => 'Semester Ganjil 2024/2025',
                ],
                [
                    'company_id' => $company->id,
                    'lowongan_id' => $lowongan->id,
                    'tanggal_mulai' => now()->subMonths(2),
                    'tanggal_selesai' => now()->addMonths(1),
                    'status_bimbingan' => 'Aktif',
                    'catatan_koordinator' => 'Sedang berlangsung.',
                ]
            );
        }

        $this->command->info(BimbinganMagang::count() . ' data bimbingan magang telah di-seed.');
    }
}