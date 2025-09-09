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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_student_id')->constrained('sms_students')->onDelete('cascade');
            $table->string('student_id'); // SMS Student ID for reference
            $table->string('rank')->nullable();
            $table->string('name');
            $table->text('current_medical_status');
            $table->text('medical_excuses')->nullable();
            $table->string('document_path')->nullable();
            $table->text('remarks')->nullable();
            $table->date('record_date');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['sms_student_id', 'record_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
