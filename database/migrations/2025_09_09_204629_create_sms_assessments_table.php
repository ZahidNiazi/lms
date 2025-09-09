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
        Schema::create('sms_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('assessment_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('total_marks', 5, 2);
            $table->decimal('obtained_marks', 5, 2);
            $table->date('assessment_date');
            $table->unsignedBigInteger('assessed_by')->nullable();
            $table->text('remarks')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('sms_subjects')->onDelete('cascade');
            $table->foreign('assessed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_assessments');
    }
};
