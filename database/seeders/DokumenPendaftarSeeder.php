<?php

namespace Database\Seeders;

use App\Models\DokumenPendaftar;
use App\Models\Pendaftar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DokumenPendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil 20 pendaftar pertama (atau semua jika kurang dari 20)
        $pendaftars = Pendaftar::take(20)->get();

        if ($pendaftars->isEmpty()) {
            $this->command->info('Tidak ada data pendaftar, DokumenPendaftarSeeder tidak dijalankan. Pastikan PendaftarSeeder dijalankan terlebih dahulu.');

            return;
        }

        // Data dokumen wajib yang akan di-seed
        $dokumenWajibData = [
            'Surat Lamaran' => 'surat_lamaran_seeder.pdf',
            'CV' => 'cv_seeder.pdf',
            'Portofolio' => 'portofolio_seeder.pdf',
            'Daftar Riwayat Hidup' => 'daftar_riwayat_hidup_seeder.pdf',
            'KHS atau Transkrip Nilai' => 'khs_transkrip_seeder.pdf',
            'KTP' => 'ktp_seeder.jpg',
            'KTM' => 'ktm_seeder.jpg',
            'Surat Izin Orang Tua' => 'surat_izin_ortu_seeder.pdf',
            'Pakta Integritas' => 'pakta_integritas_seeder.pdf',
        ];

        // Daftar dokumen opsional (jika ada yang ingin di-seed juga secara acak)
        $dokumenOpsionalData = [
            'Sertifikat Kompetensi' => 'sertifikat_kompetensi_ops_seeder.pdf',
            'Kartu BPJS atau Asuransi Lain' => 'bpjs_asuransi_ops_seeder.pdf',
            'SKTM atau KIP Kuliah' => 'sktm_kip_ops_seeder.pdf',
            'Surat Balasan Industri' => 'sbi_opsional_seeder.pdf',
        ];

        // Gabungkan semua tipe dokumen untuk pembuatan dummy file agar tidak duplikat
        $semuaDokumenUntukDummy = array_merge($dokumenWajibData, $dokumenOpsionalData);

        $dummyDir = 'dokumen_pendaftar_dummies'; // Direktori untuk menyimpan file dummy di public/storage
        if (! Storage::disk('public')->exists($dummyDir)) {
            Storage::disk('public')->makeDirectory($dummyDir);
        }

        // Buat file dummy jika belum ada
        foreach ($semuaDokumenUntukDummy as $namaDokumenDb => $namaFileDummy) {
            $fullPath = $dummyDir.'/'.$namaFileDummy;
            if (! Storage::disk('public')->exists($fullPath)) {
                // Membuat file dummy sederhana
                Storage::disk('public')->put($fullPath, "Ini adalah konten dummy untuk dokumen: {$namaDokumenDb}. Nama file: {$namaFileDummy}");
            }
        }

        $totalDokumenWajibBaru = 0;

        foreach ($pendaftars as $pendaftar) {
            // Seed semua dokumen wajib untuk pendaftar ini
            foreach ($dokumenWajibData as $namaDokumenDb => $namaFileDummy) {
                $filePathToStore = $dummyDir.'/'.$namaFileDummy; // Path relatif terhadap storage/app/public
                $fileExtension = pathinfo($namaFileDummy, PATHINFO_EXTENSION);

                // Tentukan status validasi. Untuk testing, kita bisa set beberapa jadi 'Valid'.
                // Anda bisa menyesuaikan logika ini. Contoh:
                $statusValidasi = 'Belum Diverifikasi'; // Default
                if (in_array($namaDokumenDb, ['Surat Lamaran', 'CV'])) {
                    $statusValidasi = 'Valid'; // Buat Surat Lamaran dan CV jadi Valid secara default untuk beberapa pendaftar
                } elseif (rand(0, 100) < 30) { // 30% kemungkinan jadi 'Tidak Valid' atau 'Perlu Revisi'
                    $statusValidasi = collect(['Tidak Valid', 'Perlu Revisi'])->random();
                }

                $dokumen = DokumenPendaftar::firstOrCreate(
                    [
                        'pendaftar_id' => $pendaftar->id,
                        'nama_dokumen' => $namaDokumenDb,
                    ],
                    [
                        'file_path' => $filePathToStore,
                        'tipe_file' => $fileExtension,
                        'status_validasi' => $statusValidasi, // Gunakan status yang ditentukan
                    ]
                );
                // Jika record baru dibuat oleh firstOrCreate, $dokumen->wasRecentlyCreated akan true
                if ($dokumen->wasRecentlyCreated) {
                    $totalDokumenWajibBaru++;
                }
            }

            // Opsional: Seed beberapa dokumen opsional secara acak untuk pendaftar ini
            if (! empty($dokumenOpsionalData)) {
                $jumlahOpsionalUntukPendaftar = rand(0, count($dokumenOpsionalData)); // Ambil 0 hingga semua dari opsional
                if ($jumlahOpsionalUntukPendaftar > 0) {
                    // Ambil kunci secara acak dari dokumen opsional
                    $randomKeysOpsional = array_rand($dokumenOpsionalData, $jumlahOpsionalUntukPendaftar);
                    if (! is_array($randomKeysOpsional)) { // array_rand bisa mengembalikan satu kunci jika jumlahnya 1
                        $randomKeysOpsional = [$randomKeysOpsional];
                    }

                    foreach ($randomKeysOpsional as $namaDokumenDbOps) {
                        $namaFileDummyOps = $dokumenOpsionalData[$namaDokumenDbOps];
                        $filePathToStoreOps = $dummyDir.'/'.$namaFileDummyOps;
                        $fileExtensionOps = pathinfo($namaFileDummyOps, PATHINFO_EXTENSION);

                        $statusValidasiOps = collect(['Belum Diverifikasi', 'Valid'])->random(); // Acak antara Belum Diverifikasi dan Valid

                        DokumenPendaftar::firstOrCreate(
                            [
                                'pendaftar_id' => $pendaftar->id,
                                'nama_dokumen' => $namaDokumenDbOps,
                            ],
                            [
                                'file_path' => $filePathToStoreOps,
                                'tipe_file' => $fileExtensionOps,
                                'status_validasi' => $statusValidasiOps,
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Proses seeding DokumenPendaftar telah selesai.');
        if ($totalDokumenWajibBaru > 0) {
            $this->command->info("{$totalDokumenWajibBaru} entri dokumen wajib pendaftar baru telah di-seed dengan status bervariasi.");
        } else {
            $this->command->info('Semua dokumen wajib untuk pendaftar yang diproses sudah ada di database.');
        }
        if (! empty($dokumenOpsionalData) && $pendaftars->isNotEmpty()) {
            $this->command->info('Beberapa dokumen opsional mungkin juga telah ditambahkan/dipastikan ada.');
        }
    }
}
