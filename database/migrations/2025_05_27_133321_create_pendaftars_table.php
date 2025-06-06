<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Mahasiswa yang mendaftar
            $table->foreignId('lowongan_id')->constrained('lowongans')->onDelete('cascade'); // Lowongan yang dilamar
            $table->date('tanggal_daftar');
            $table->enum('status_lamaran', ['Pending', 'Ditinjau', 'Wawancara', 'Diterima', 'Ditolak'])->default('Pending');
            $table->string('surat_lamaran_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('portofolio_path')->nullable();
            $table->text('catatan_pendaftar')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lowongan_id']); // Seorang mahasiswa hanya bisa mendaftar sekali ke satu lowongan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
