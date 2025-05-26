<?php
// File: 2024_01_01_000008_create_internship_postings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internship_postings', function (Blueprint $table) {
            $table->id('posting_id');
            $table->foreignId('company_id')->constrained('companies', 'company_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->text('responsibilities');
            $table->string('location');
            $table->boolean('is_remote')->default(false);
            $table->string('department');
            $table->integer('position_available')->default(1);
            $table->boolean('is_paid')->default(false);
            $table->string('compensation')->nullable();
            $table->integer('duration_weeks');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('application_deadline');
            $table->enum('status', ['draft', 'published', 'closed', 'deleted'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_postings');
    }
};
