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
        Schema::table('bimbingan_fotos', function (Blueprint $table) {
            $table->unsignedBigInteger('bimbingan_id')->after('id');

            // Add foreign key constraint if needed
            $table->foreign('bimbingan_id')->references('id')->on('bimbingan_magangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bimbingan_fotos', function (Blueprint $table) {
            $table->dropForeign(['bimbingan_id']);
            $table->dropColumn('bimbingan_id');
        });
    }

};
