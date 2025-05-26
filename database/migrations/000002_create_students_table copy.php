<?php
// File: 2024_01_01_000002_create_students_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('nim')->unique();
            $table->string('name');
            $table->string('program_study');
            $table->integer('semester');
            $table->decimal('gpa', 8, 2);
            $table->string('phone');
            $table->text('address');
            $table->string('profile_picture')->nullable();
            $table->string('resume_path')->nullable();
            $table->boolean('looking_for_internship')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
