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
        Schema::create('pembimbings', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment sebagai primary key
            
            // Foreign key ke tabel users (jika pembimbing adalah user sistem)
            // Bisa dibuat unique jika satu user hanya bisa jadi satu entri pembimbing
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null'); 
            
            $table->string('nip')->unique(); // Nomor Induk Pegawai/Dosen, unik
            $table->string('nama_lengkap');
            $table->string('email_institusi')->unique(); // Email resmi institusi
            $table->string('nomor_telepon')->nullable();
            $table->string('jabatan_fungsional')->nullable(); // Misal: Lektor, Lektor Kepala
            $table->string('program_studi_homebase')->nullable(); // Prodi utama dosen
            $table->text('bidang_keahlian_utama')->nullable();
            $table->integer('kuota_bimbingan_aktif')->default(0); // Jumlah mahasiswa yang sedang dibimbing
            $table->integer('maks_kuota_bimbingan')->default(10); // Kuota maksimal
            $table->boolean('status_aktif')->default(true); // Apakah pembimbing ini aktif atau tidak
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembimbings');
    }
};