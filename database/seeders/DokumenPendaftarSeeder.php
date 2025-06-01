<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DokumenPendaftar;
use App\Models\Pendaftar; // Kita butuh Pendaftar untuk mendapatkan pendaftar_id
use Illuminate\Support\Facades\Storage; // Untuk mengelola file dummy jika diperlukan

class DokumenPendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa pendaftar yang sudah ada (misalnya 5 pendaftar pertama)
        // Pastikan PendaftarSeeder atau data pendaftar sudah ada
        $pendaftars = Pendaftar::take(5)->get();

        if ($pendaftars->isEmpty()) {
            $this->command->info('Tidak ada data pendaftar ditemukan, DokumenPendaftarSeeder tidak dijalankan.');
            return;
        }

        $tipeDokumenUmum = [
            'CV' => 'cv.pdf',
            'Surat Lamaran' => 'surat_lamaran.pdf',
            'Transkrip Nilai' => 'transkrip.pdf',
            'KTP' => 'ktp.jpg',
            'Sertifikat Keahlian' => 'sertifikat_keahlian.pdf',
        ];

        // Membuat direktori dummy jika belum ada
        if (!Storage::disk('public')->exists('dokumen_pendaftar_dummies')) {
            Storage::disk('public')->makeDirectory('dokumen_pendaftar_dummies');
        }

        // Membuat file dummy jika belum ada
        foreach ($tipeDokumenUmum as $nama => $file) {
            if (!Storage::disk('public')->exists('dokumen_pendaftar_dummies/' . $file)) {
                Storage::disk('public')->put('dokumen_pendaftar_dummies/' . $file, 'Ini adalah konten file dummy untuk ' . $nama);
            }
        }


        foreach ($pendaftars as $pendaftar) {
            // Untuk setiap pendaftar, tambahkan beberapa dokumen umum
            foreach ($tipeDokumenUmum as $namaDokumen => $namaFileDummy) {
                DokumenPendaftar::create([
                    'pendaftar_id' => $pendaftar->id,
                    'nama_dokumen' => $namaDokumen,
                    // Simpan path relatif ke direktori public/storage
                    'file_path' => 'dokumen_pendaftar_dummies/' . $namaFileDummy,
                    'tipe_file' => pathinfo($namaFileDummy, PATHINFO_EXTENSION), // Mendapatkan ekstensi file
                ]);
            }

            // Contoh menambahkan dokumen spesifik untuk pendaftar tertentu jika diperlukan
            if ($pendaftar->id % 2 == 0) { // Hanya untuk pendaftar dengan ID genap misalnya
                $namaFilePortofolio = 'portofolio_pendaftar_' . $pendaftar->id . '.pdf';
                if (!Storage::disk('public')->exists('dokumen_pendaftar_dummies/' . $namaFilePortofolio)) {
                    Storage::disk('public')->put('dokumen_pendaftar_dummies/' . $namaFilePortofolio, 'Konten portofolio untuk pendaftar ' . $pendaftar->id);
                }
                DokumenPendaftar::create([
                    'pendaftar_id' => $pendaftar->id,
                    'nama_dokumen' => 'Portofolio Proyek',
                    'file_path' => 'dokumen_pendaftar_dummies/' . $namaFilePortofolio,
                    'tipe_file' => 'pdf',
                ]);
            }
        }

        $this->command->info( ($pendaftars->count() * count($tipeDokumenUmum)) . ' dokumen pendaftar umum telah ditambahkan.');
    }
}