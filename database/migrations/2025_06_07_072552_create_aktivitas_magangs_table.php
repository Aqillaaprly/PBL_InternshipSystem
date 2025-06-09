<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAktivitasMagangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aktivitas_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas'); 
            $table->date('tanggal');
            $table->text('deskripsi_kegiatan');
            $table->integer('jam_kerja')->nullable(); 
            $table->string('status_verifikasi')->default('pending'); 
            $table->unsignedBigInteger('dosen_pembimbing_id')->nullable(); 
            $table->unsignedBigInteger('perusahaan_pic_id')->nullable(); 
            $table->string('bukti_kegiatan')->nullable(); 
            $table->text('catatan_verifikasi_dosen')->nullable();
            $table->text('catatan_verifikasi_perusahaan')->nullable();
            $table->timestamps();
            $table->foreign('dosen_pembimbing_id')->references('id')->on('users');
            $table->foreign('perusahaan_pic_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aktivitas_magangs');
    }
}