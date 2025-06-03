<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dokumen_pendaftars', function (Blueprint $table) {
            // Pastikan kolom 'tipe_file' sudah ada sebelum menambahkan 'status_validasi' setelahnya.
            // Jika 'tipe_file' tidak ada atau Anda ingin menambahkannya di akhir, hapus ->after('tipe_file').
            if (Schema::hasColumn('dokumen_pendaftars', 'tipe_file')) {
                $table->enum('status_validasi', ['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Perlu Revisi'])
                      ->default('Belum Diverifikasi')
                      ->after('tipe_file');
            } else {
                $table->enum('status_validasi', ['Belum Diverifikasi', 'Valid', 'Tidak Valid', 'Perlu Revisi'])
                      ->default('Belum Diverifikasi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_pendaftars', function (Blueprint $table) {
            $table->dropColumn('status_validasi');
        });
    }
};