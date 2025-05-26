<?php
// File: 2024_01_01_000013_create_student_feedback_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('report_id')->constrained('student_reports', 'report_id')->onDelete('cascade');
            $table->integer('rating')->check('rating >= 1 AND rating <= 5');
            $table->text('comments');
            $table->integer('learning_experience')->check('learning_experience >= 1 AND learning_experience <= 5');
            $table->integer('work_environment')->check('work_environment >= 1 AND work_environment <= 5');
            $table->integer('supervision_quality')->check('supervision_quality >= 1 AND supervision_quality <= 5');
            $table->boolean('would_recommend')->default(false);
            $table->timestamp('feedback_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_feedback');
    }
};
