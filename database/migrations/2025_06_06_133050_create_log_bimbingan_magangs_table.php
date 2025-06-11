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
        Schema::create('log_bimbingan_magangs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('bimbingan_magang_id')->constrained('bimbingan_magangs')->onDelete('cascade');
        $table->string('metode_bimbingan');
        $table->date('waktu_bimbingan');
        $table->string('topik_bimbingan');
        $table->text('deskripsi');
        $table->decimal('nilai', 5, 2);
        $table->text('komentar')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_bimbingan_magangs');
    }
};
