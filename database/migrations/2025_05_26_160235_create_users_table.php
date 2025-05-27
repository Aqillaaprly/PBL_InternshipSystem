<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Tambahkan ini (bisa nullable atau tidak sesuai kebutuhan)
            $table->string('email')->unique()->nullable(); // Tambahkan ini, buat unik dan nullable jika username adalah login utama
            $table->timestamp('email_verified_at')->nullable(); // Tambahkan ini
            $table->string('username')->unique();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};