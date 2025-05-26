<?php
// File: 2024_01_01_000012_create_student_reports_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->foreignId('internship_id')->constrained('internships', 'internship_id')->onDelete('cascade');
            $table->enum('report_type', ['daily', 'weekly', 'final']);
            $table->string('title');
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->timestamp('submission_date')->useCurrent();
            $table->enum('status', ['submitted', 'reviewed', 'approved', 'rejected'])->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_reports');
    }
};