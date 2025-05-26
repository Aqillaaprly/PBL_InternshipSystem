<?php
// File: 2024_01_01_000015_create_company_feedback_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('internship_id')->constrained('internships', 'internship_id')->onDelete('cascade');
            $table->integer('rating')->check('rating >= 1 AND rating <= 5');
            $table->text('strengths');
            $table->text('areas_for_improvement');
            $table->boolean('recommendation')->default(false);
            $table->text('comments');
            $table->timestamp('feedback_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_feedback');
    }
};
