<?php
// File: 2024_01_01_000004_create_companies_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('company_name');
            $table->string('industry');
            $table->text('description');
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state_province');
            $table->string('postal_code');
            $table->string('country');
            $table->string('phone');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
