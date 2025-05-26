<?php
// File: 2024_01_01_000006_create_student_preferences_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id('preference_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->string('preferred_location');
            $table->string('preferred_industry');
            $table->string('preferred_job_role');
            $table->enum('remote_preference', ['remote', 'hybrid', 'on-site', 'no-preference']);
            $table->decimal('min_salary', 8, 2)->nullable();
            $table->date('start_availability');
            $table->date('end_availability');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_preferences');
    }
};