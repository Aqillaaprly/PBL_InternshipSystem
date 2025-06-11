<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbsensiMagang;
use App\Models\BimbinganMagang;

class AbsensiMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $bimbingans = BimbinganMagang::all();

        foreach ($bimbingans as $bimbingan) {
            for ($i = 1; $i <= 5; $i++) { // Buat 5 absensi per bimbingan
                AbsensiMagang::firstOrCreate(
                    [
                        'bimbingan_magang_id' => $bimbingan->id, // perbaikan di sini
                        'tanggal' => now()->subDays($i),
                    ],
                    [
                        'status' => ['Hadir', 'Izin', 'Sakit'][rand(0, 2)],
                        'catatan' => 'Pertemuan ke-' . $i,
                    ]
                );
            }
        }

        $this->command->info(AbsensiMagang::count() . ' data absensi mahasiswa telah di-seed.');
    }
}