<?php
// File: 2024_01_01_000011_create_internship_recommendations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_recommendations', function (Blueprint $table) {
            $table->id('recommendation_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->foreignId('posting_id')->constrained('internship_postings', 'posting_id')->onDelete('cascade');
            $table->decimal('match_score', 8, 2);
            $table->timestamp('recommendation_date')->useCurrent();
            $table->boolean('is_viewed')->default(false);
            $table->integer('ranking');
            $table->foreignId('survey_id')->constrained('student_surveys', 'survey_id')->onDelete('cascade');
            $table->foreignId('preference_id')->constrained('student_preferences', 'preference_id')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('student_skills', 'skill_id')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('applications', 'application_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_recommendations');
    }
};