<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');
            $table->string('nama_dokumen'); // Misal: KTP, KTM, CV, Surat Lamaran
            $table->string('file_path');   // Path ke file yang diunggah
            $table->string('tipe_file')->nullable(); // Misal: pdf, jpg, png
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendaftars');
    }
};
