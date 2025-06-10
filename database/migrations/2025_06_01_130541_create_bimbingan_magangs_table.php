<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimbinganMagangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan_magangs', function (Blueprint $table) {
            $table->id();
            // PERBAIKI: Ubah dari 'mahasiswa_id' menjadi 'mahasiswa_user_id' dan constrained ke 'users'
            // Ini agar sesuai dengan model BimbinganMagang yang berelasi ke User::class melalui 'mahasiswa_user_id'
            $table->foreignId('mahasiswa_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembimbing_id')->constrained('pembimbings')->onDelete('cascade');

            // TAMBAHKAN KOLOM-KOLOM INI yang ada di $fillable model BimbinganMagang
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongans')->onDelete('cascade');
            $table->string('periode_magang');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status_bimbingan')->default('Aktif');
            $table->text('catatan_koordinator')->nullable();

            // Kolom yang sudah ada di migrasi Anda sebelumnya, pastikan tetap ada
            $table->string('jenis_bimbingan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bimbingan_magangs');
    }
}