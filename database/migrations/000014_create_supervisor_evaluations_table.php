<?php
// File: 2024_01_01_000014_create_supervisor_evaluations_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisor_evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            $table->foreignId('internship_id')->constrained('internships', 'internship_id')->onDelete('cascade');
            $table->foreignId('report_id')->nullable()->constrained('student_reports', 'report_id')->onDelete('set null');
            $table->integer('rating')->check('rating >= 1 AND rating <= 5');
            $table->text('comments');
            $table->timestamp('evaluation_date')->useCurrent();
            $table->enum('evaluation_type', ['progress', 'report', 'final']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisor_evaluations');
    }
};
