<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use App\Models\Pendaftar;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon; // Pastikan Role di-import jika digunakan untuk mengambil mahasiswa
use Illuminate\Database\Seeder;

class PendaftarSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        if (! $mahasiswaRole) {
            $this->command->error("Role 'mahasiswa' tidak ditemukan. PendaftarSeeder tidak dapat berjalan.");

            return;
        }

        $mahasiswas = User::where('role_id', $mahasiswaRole->id)->get();
        $lowongans = Lowongan::where('status', 'Aktif')->get();

        if ($mahasiswas->isEmpty() || $lowongans->isEmpty()) {
            $this->command->warn('Tidak ada mahasiswa atau lowongan aktif yang ditemukan. PendaftarSeeder tidak membuat data.');

            return;
        }

        $jumlahPendaftarDibuat = 0;

        foreach ($mahasiswas as $mahasiswa) {
            // Setiap mahasiswa mendaftar ke 1-2 lowongan acak
            $jumlahLamaran = rand(1, min(2, $lowongans->count()));
           $lowongansDipilih = $lowongans->random($jumlahLamaran);

            foreach ($lowongansDipilih as $lowongan) {
                // Cek apakah pendaftar sudah ada untuk kombinasi user dan lowongan ini
                $existingPendaftar = Pendaftar::where('user_id', $mahasiswa->id)
                    ->where('lowongan_id', $lowongan->id)
                    ->first();

                 $existingPendaftar = Pendaftar::where('user_id', $mahasiswa->id)
                                            ->where('lowongan_id', $lowongan->id)
                                            ->first();

                if (!$existingPendaftar) {
                    Pendaftar::create(
                        [
                            'user_id' => $mahasiswa->id,
                            'lowongan_id' => $lowongan->id,
                            'tanggal_daftar' => Carbon::now()->subDays(rand(1, 30))->toDateString(),
                            'status_lamaran' => 'Pending', // <-- PERUBAHAN DI SINI: Set default ke Pending
                            'surat_lamaran_path' => 'dokumen_pendaftar_dummies/surat_lamaran_contoh.pdf',
                            'cv_path' => 'dokumen_pendaftar_dummies/cv_contoh.pdf',
                            'catatan_pendaftar' => 'Saya sangat tertarik dengan posisi ini.',
                        ]
                    );
                    $jumlahPendaftarDibuat++;
                }
            }
        }  
        if ($jumlahPendaftarDibuat > 0) {
            $this->command->info($jumlahPendaftarDibuat . ' data pendaftar baru telah di-seed dengan status lamaran "Pending".');
        } else {
            $this->command->info('Tidak ada data pendaftar baru yang di-seed (mungkin semua kombinasi sudah ada).');
        }
        
        $this->command->info('Memperbarui status lamaran pendaftar yang sudah ada menjadi "Pending" (kecuali Ditolak)...');
        $updatedCount = Pendaftar::whereNotIn('status_lamaran', ['Pending', 'Ditolak'])
                                 ->update(['status_lamaran' => 'Pending']);
        if ($updatedCount > 0) {
            $this->command->info($updatedCount . ' status pendaftar yang sudah ada telah diubah menjadi "Pending".');
        } else {
            $this->command->info('Tidak ada status pendaftar yang perlu diubah (semua sudah Pending atau Ditolak).');
        } 
    }
}
