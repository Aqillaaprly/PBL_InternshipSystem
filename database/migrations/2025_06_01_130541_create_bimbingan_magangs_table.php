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
            // Menambahkan foreign keys ke tabel mahasiswa dan pembimbing
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('pembimbing_id')->constrained('pembimbings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('jenis_bimbingan');
            $table->text('catatan');
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
