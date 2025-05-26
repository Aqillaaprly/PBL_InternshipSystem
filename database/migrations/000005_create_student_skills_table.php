<?php
// File: 2024_01_01_000005_create_student_skills_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id('skill_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->string('skill_name');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert']);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_skills');
    }
};
