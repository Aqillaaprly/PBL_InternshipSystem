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
        Schema::create('bimbingan_fotos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('bimbingan_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bimbingan_id');
            $table->string('path'); // for image file path
            $table->timestamps();

            $table->foreign('bimbingan_id')->references('id')->on('bimbingan_magangs')->onDelete('cascade');
        });

    }
};
