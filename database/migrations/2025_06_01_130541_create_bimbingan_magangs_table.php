<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbingan_magangs', function (Blueprint $table) {
            $table->id();
            // Merujuk ke user_id mahasiswa, bukan mahasiswa.id agar konsisten jika mahasiswa adalah user
            $table->foreignId('mahasiswa_user_id')->constrained('users')->comment('User ID Mahasiswa');
            $table->foreignId('pembimbing_id')->constrained('pembimbings')->comment('ID dari tabel Pembimbings');
            $table->foreignId('company_id')->nullable()->constrained('companies')->comment('Tempat magang mahasiswa');
            $table->foreignId('lowongan_id')->nullable()->constrained('lowongans')->comment('Lowongan yang diambil mahasiswa');
            $table->string('periode_magang')->nullable()->comment('Contoh: Semester Ganjil 2024/2025');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status_bimbingan', ['Aktif', 'Selesai', 'Dibatalkan'])->default('Aktif');
            $table->text('catatan_koordinator')->nullable();
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi penugasan
            $table->unique(['mahasiswa_user_id', 'pembimbing_id', 'periode_magang'], 'unique_bimbingan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingan_magangs');
    }
};
