<?php
// File: 2024_01_01_000010_create_internships_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internships', function (Blueprint $table) {
            $table->id('internship_id');
            $table->foreignId('application_id')->constrained('applications', 'application_id')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies', 'company_id')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('supervisors', 'supervisor_id')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'ongoing', 'completed', 'terminated'])->default('pending');
            $table->timestamp('assigned_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internships');
    }
};
