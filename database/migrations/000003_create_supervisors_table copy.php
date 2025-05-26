<?php
// File: 2024_01_01_000003_create_supervisors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->id('supervisor_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('name');
            $table->string('position');
            $table->string('department');
            $table->string('specialization');
            $table->string('phone');
            $table->integer('max_student')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
