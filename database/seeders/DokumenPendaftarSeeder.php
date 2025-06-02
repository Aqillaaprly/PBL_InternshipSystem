<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DokumenPendaftar;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DokumenPendaftarSeeder extends Seeder
{
    public function run(): void
    {
        $pendaftars = Pendaftar::take(10)->get(); // Ambil lebih banyak pendaftar jika perlu

        if ($pendaftars->isEmpty()) {
            $this->command->info('Tidak ada data pendaftar, DokumenPendaftarSeeder tidak dijalankan.');
            return;
        }

        $predefinedDokumenTypes = [
            'Sertifikat Kompetensi' => 'sertifikat_kompetensi.pdf',
            'Surat Balasan Industri' => 'surat_balasan_industri.pdf',
            'Pakta Integritas' => 'pakta_integritas.pdf',
            'Daftar Riwayat Hidup' => 'cv.pdf',
            'KHS atau Transkrip Nilai' => 'khs_transkrip.pdf',
            'KTP' => 'ktp.jpg',
            'KTM' => 'ktm.jpg',
            'Surat Izin Orang Tua' => 'surat_izin_ortu.pdf',
            'Kartu BPJS atau Asuransi Lain' => 'bpjs_asuransi.pdf',
            'SKTM atau KIP Kuliah' => 'sktm_kip.pdf',
            'Proposal Magang' => 'proposal_magang.pdf',
        ];

        $dummyDir = 'dokumen_pendaftar_dummies';
        if (!Storage::disk('public')->exists($dummyDir)) {
            Storage::disk('public')->makeDirectory($dummyDir);
        }

        $dummyFilePaths = [];
        foreach ($predefinedDokumenTypes as $namaDokumen => $namaFile) {
            $fullPath = $dummyDir . '/' . $namaFile;
            if (!Storage::disk('public')->exists($fullPath)) {
                Storage::disk('public')->put($fullPath, "Dummy content for {$namaDokumen}. Original: {$namaFile}");
            }
            $dummyFilePaths[$namaDokumen] = $fullPath;
        }

        $totalDokumenDibuat = 0;
        $statuses = ['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Perlu Revisi'];

        foreach ($pendaftars as $pendaftar) {
            $jumlahDokumenUntukPendaftar = rand(5, count($predefinedDokumenTypes));
            $dokumenDipilihUntukPendaftar = array_rand($predefinedDokumenTypes, $jumlahDokumenUntukPendaftar);
            
            if (!is_array($dokumenDipilihUntukPendaftar)) {
                $dokumenDipilihUntukPendaftar = [$dokumenDipilihUntukPendaftar];
            }

            foreach ($dokumenDipilihUntukPendaftar as $namaDokumenKey) {
                $filePathToStore = $dummyFilePaths[$namaDokumenKey];
                $fileExtension = pathinfo($predefinedDokumenTypes[$namaDokumenKey], PATHINFO_EXTENSION);

                DokumenPendaftar::firstOrCreate(
                    [
                        'pendaftar_id' => $pendaftar->id,
                        'nama_dokumen' => $namaDokumenKey,
                    ],
                    [
                        'file_path' => $filePathToStore,
                        'tipe_file' => $fileExtension,
                        // Secara acak menentukan status validasi, atau set default 'Belum Diverifikasi'
                        'status_validasi' => $statuses[array_rand($statuses)],
                        // 'status_validasi' => 'Belum Diverifikasi', // Default yang lebih aman
                    ]
                );
                $totalDokumenDibuat++;
            }
        }

        if ($totalDokumenDibuat > 0) {
            $this->command->info($totalDokumenDibuat . ' data dokumen pendaftar telah di-seed dengan status validasi.');
        } else {
            $this->command->warn('Tidak ada dokumen pendaftar baru yang di-seed.');
        }
    }
}