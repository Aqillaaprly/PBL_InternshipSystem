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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment sebagai primary key
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Foreign key ke tabel users (mahasiswa sebagai user)
            $table->string('nim')->unique(); // Nomor Induk Mahasiswa, harus unik
            $table->string('nama'); // Nama lengkap mahasiswa
            $table->string('email')->unique(); // Email mahasiswa, harus unik
            $table->string('kelas')->nullable(); // Contoh: TI 2L
            $table->string('program_studi')->nullable(); // Contoh: Teknik Informatika
            $table->string('nomor_hp')->nullable(); // Nomor telepon/HP
            $table->text('alamat')->nullable(); // Alamat lengkap
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};