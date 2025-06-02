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
       Schema::create('lowongans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
    $table->string('judul');
    $table->text('deskripsi');
    $table->text('kualifikasi');
    $table->enum('tipe', ['Penuh Waktu', 'Paruh Waktu', 'Kontrak', 'Internship']);
    $table->string('lokasi');
    $table->decimal('gaji_min', 15, 2)->nullable();
    $table->decimal('gaji_max', 15, 2)->nullable();
    $table->date('tanggal_buka');
    $table->date('tanggal_tutup');
    $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongans');
    }
};
