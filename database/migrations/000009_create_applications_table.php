<?php
// File: 2024_01_01_000009_create_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id('application_id');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade');
            $table->foreignId('posting_id')->constrained('internship_postings', 'posting_id')->onDelete('cascade');
            $table->text('cover_letter')->nullable();
            $table->string('additional_documents')->nullable();
            $table->enum('status', [
                'pending', 
                'shortlisted', 
                'interviewed', 
                'offered', 
                'accepted', 
                'rejected', 
                'withdrawn'
            ])->default('pending');
            $table->timestamp('application_date')->useCurrent();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};