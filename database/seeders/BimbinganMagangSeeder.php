<?php

namespace Database\Seeders;

use App\Models\BimbinganMagang;
use App\Models\Mahasiswa;
use App\Models\Pembimbing;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BimbinganMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mahasiswas = Mahasiswa::all();
        $pembimbings = Pembimbing::all();

        if ($mahasiswas->isEmpty() || $pembimbings->isEmpty()) {
            $this->command->info('Tidak ada mahasiswa atau pembimbing. Jalankan `php artisan db:seed --class=MahasiswaSeeder` dan `php artisan db:seed --class=PembimbingSeeder` terlebih dahulu.');

            return;
        }

        // Contoh data aktivitas
        $activities = [
            [
                'mahasiswa_id' => $mahasiswas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jenis_bimbingan' => 'Konsultasi Proyek',
                'catatan' => 'Membahas progress proyek akhir, kendala teknis pada implementasi fitur X.',
            ],
            [
                'mahasiswa_id' => $mahasiswas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jenis_bimbingan' => 'Review Laporan',
                'catatan' => 'Melakukan review draf bab 1 dan bab 2 laporan magang. Perlu perbaikan di bagian metodologi.',
            ],
            [
                'mahasiswa_id' => $mahasiswas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jenis_bimbingan' => 'Diskusi Absensi',
                'catatan' => 'Mahasiswa melaporkan sakit, melampirkan surat dokter.',
            ],
            [
                'mahasiswa_id' => $mahasiswas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jenis_bimbingan' => 'Presentasi Progress',
                'catatan' => 'Presentasi hasil kerja minggu ke-3. Hasil cukup baik, perlu optimalisasi performa.',
            ],
            [
                'mahasiswa_id' => $mahasiswas->random()->id,
                'pembimbing_id' => $pembimbings->random()->id,
                'tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'jenis_bimbingan' => 'Pengisian Logbook',
                'catatan' => 'Memastikan pengisian logbook harian sesuai dengan aktivitas yang dilakukan.',
            ],
        ];

        foreach ($activities as $activity) {
            BimbinganMagang::create($activity);
        }
    }
}
