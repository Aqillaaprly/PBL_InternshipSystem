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
        Schema::create('detail_pembimbings', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment sebagai primary key
            
            // Foreign key ke tabel users (dosen sebagai user)
            // Pastikan user_id ini unik jika satu user hanya boleh punya satu profil pembimbing
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade'); 
            
            $table->string('nip')->unique()->nullable(); // Nomor Induk Pegawai/Dosen, unik dan bisa kosong jika belum ada
            $table->string('jabatan_fungsional')->nullable(); // Misalnya: Lektor, Asisten Ahli, Guru Besar
            $table->string('program_studi_pengampu')->nullable(); // Prodi tempat dosen mengajar
            $table->text('bidang_keahlian')->nullable(); // Bisa berupa string dipisahkan koma atau JSON
            $table->string('nomor_telepon_kantor')->nullable();
            $table->string('ruang_kantor')->nullable();
            $table->integer('kuota_bimbingan_utama')->default(5); // Jumlah maks mahasiswa bimbingan utama
            $table->integer('kuota_bimbingan_pendamping')->default(5); // Jumlah maks mahasiswa bimbingan pendamping
            $table->text('catatan_tambahan')->nullable(); // Catatan lain tentang pembimbing
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembimbings');
    }
};