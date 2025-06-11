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
            $table->text('kualifikasi')->nullable(); // Made nullable based on HTML form's optional status
            $table->text('tanggung_jawab')->nullable(); // Added tanggung_jawab as it's in the form

            // Updated 'tipe' enum values to match HTML form
            $table->enum('tipe', ['Full-time', 'Part-time', 'Magang', 'Kontrak']);

            // Removed 'lokasi' and added detailed address fields
            $table->string('provinsi'); // Mark as not nullable if it's required in the form
            $table->string('kota');     // Mark as not nullable if it's required in the form
            $table->string('alamat')->nullable();   // Added alamat as nullable (optional in form)
            $table->string('kode_pos', 10)->nullable(); // Added kode_pos as nullable (optional in form)

            $table->decimal('gaji_min', 15, 2)->nullable();
            $table->decimal('gaji_max', 15, 2)->nullable();
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            
            // Updated 'status' enum values to match HTML form
            $table->enum('status', ['Aktif', 'Nonaktif', 'Ditutup'])->default('Aktif');
            
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
