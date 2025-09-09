<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_student_id')->constrained('sms_students')->onDelete('cascade');
            $table->string('subject');
            $table->string('course_name');
            $table->string('assessment_type'); // exam, assignment, practical, project
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->string('grade')->nullable(); // A, B, C, D, F
            $table->text('remarks')->nullable();
            $table->date('assessment_date');
            $table->foreignId('assessed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['sms_student_id', 'subject']);
            $table->index(['assessment_date', 'course_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
