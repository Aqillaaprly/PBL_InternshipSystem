<?php

namespace Database\Seeders;

use App\Models\AktivitasMagang;
use App\Models\Mahasiswa; // Pastikan ini diimport
use App\Models\User;     // Mungkin masih diperlukan jika Anda ingin memfilter user_id di Mahasiswa
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker; // Import Faker

class AktivitasMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil semua data mahasiswa yang sudah ada di tabel 'mahasiswas'
        $mahasiswas = Mahasiswa::all(); // Mengambil semua Mahasiswa

        if ($mahasiswas->isEmpty()) {
            $this->command->info('Tidak ada data mahasiswa di tabel mahasiswas. AktivitasMagangSeeder tidak dijalankan.');
            return;
        }

        foreach ($mahasiswas as $mahasiswa) { // Iterasi melalui model Mahasiswa
            // Untuk setiap mahasiswa, buat beberapa entri aktivitas harian
            $numActivities = $faker->numberBetween(5, 15); // Misalnya 5 sampai 15 aktivitas per mahasiswa

            for ($i = 0; $i < $numActivities; $i++) {
                $tanggalAktivitas = Carbon::now()->subDays($faker->numberBetween(1, 60)); // Aktivitas dalam 60 hari terakhir
                $statusVerifikasi = $faker->randomElement(['pending', 'diverifikasi_dosen', 'ditolak']);

                AktivitasMagang::firstOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswa->id, // GUNAKAN ID DARI MODEL MAHASISWA
                        'tanggal' => $tanggalAktivitas->toDateString(),
                        'deskripsi_kegiatan' => $faker->sentence(rand(5, 15)),
                    ],
                    [
                        'jam_kerja' => $faker->numberBetween(4, 8),
                        'status_verifikasi' => $statusVerifikasi,
                        // 'dosen_pembimbing_id' dan 'perusahaan_pic_id' bisa diisi jika status diverifikasi
                        // Untuk contoh sederhana, biarkan null atau isi dengan ID dummy jika ada
                        // 'bukti_kegiatan' => $faker->imageUrl(640, 480, 'activities'), // Jika ingin dummy gambar
                        'catatan_verifikasi_dosen' => ($statusVerifikasi !== 'pending') ? $faker->sentence(5) : null,
                    ]
                );
            }
        }
        $this->command->info('Data aktivitas magang harian telah di-seed.');
    }
}