<?php
// File: 2024_01_01_000007_create_student_surveys_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_surveys', function (Blueprint $table) {
            $table->id('survey_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->timestamp('submission_date')->useCurrent();
            $table->string('internship_period');
            $table->text('career_goals');
            $table->text('technical_skills');
            $table->text('soft_skills');
            $table->text('preferred_work_culture');
            $table->enum('survey_status', ['draft', 'submitted', 'processed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_surveys');
    }
};