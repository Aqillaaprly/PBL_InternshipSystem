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
        Schema::create('absensi_magangs', function (Blueprint $table) {
            $table->id();
            
            // foreign key ke tabel bimbingan_magangs
            $table->foreignId('bimbingan_magang_id')->constrained('bimbingan_magangs')->onDelete('cascade');
            
            // tanggal absensi
            $table->date('tanggal');
            
            // status absensi
            $table->enum('status', ['Hadir', 'Izin', 'Sakit'])->default('Hadir');
            
            // catatan tambahan
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_magangs');
    }
};
