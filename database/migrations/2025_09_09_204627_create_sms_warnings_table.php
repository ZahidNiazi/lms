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
        Schema::create('sms_warnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('warning_type');
            $table->text('warning_reason');
            $table->text('description')->nullable();
            $table->date('warning_date');
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->enum('severity_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->string('document_path')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_warnings');
    }
};
