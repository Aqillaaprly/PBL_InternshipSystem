<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\Lowongan;
use App\Models\Role;
use Carbon\Carbon;

class PendaftarSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if (!$mahasiswaRole) {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. PendaftarSeeder tidak dapat berjalan.");
            return;
        }

        $mahasiswas = User::where('role_id', $mahasiswaRole->id)->get();
        $lowongans = Lowongan::where('status', 'Aktif')->get();

        if ($mahasiswas->isEmpty() || $lowongans->isEmpty()) {
            $this->command->warn("Tidak ada mahasiswa atau lowongan aktif yang ditemukan. PendaftarSeeder tidak membuat data.");
            return;
        }

        // Setiap mahasiswa mendaftar ke 1-2 lowongan acak
        foreach ($mahasiswas as $mahasiswa) {
            $jumlahLamaran = rand(1, min(2, $lowongans->count())); // Mendaftar maksimal 2 atau sejumlah lowongan yang ada
            $lowongansDipilih = $lowongans->random($jumlahLamaran);

            foreach ($lowongansDipilih as $lowongan) {
                // Hindari duplikasi pendaftaran
                Pendaftar::firstOrCreate(
                    [
                        'user_id' => $mahasiswa->id,
                        'lowongan_id' => $lowongan->id,
                    ],
                    [
                        'tanggal_daftar' => Carbon::now()->subDays(rand(1, 30))->toDateString(),
                        'status_lamaran' => ['Pending', 'Ditinjau', 'Wawancara', 'Diterima', 'Ditolak'][array_rand(['Pending', 'Ditinjau', 'Wawancara', 'Diterima', 'Ditolak'])],
                        'surat_lamaran_path' => 'dokumen_pendaftar_dummies/surat_lamaran_contoh.pdf', // Path dummy
                        'cv_path' => 'dokumen_pendaftar_dummies/cv_contoh.pdf', // Path dummy
                        'catatan_pendaftar' => 'Saya sangat tertarik dengan posisi ini.',
                    ]
                );
            }
        }
        $this->command->info(Pendaftar::count() . ' data pendaftar telah di-seed.');
    }
}