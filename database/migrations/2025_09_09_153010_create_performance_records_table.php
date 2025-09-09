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
        Schema::create('performance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_student_id')->constrained('sms_students')->onDelete('cascade');
            $table->string('performance_type'); // skills, counselling, pay_steepens, statements, performance_indicator, observation
            $table->string('title');
            $table->text('description');
            $table->text('comments')->nullable();
            $table->string('document_path')->nullable();
            $table->decimal('score', 5, 2)->nullable(); // for performance indicators
            $table->date('record_date');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['sms_student_id', 'performance_type']);
            $table->index(['record_date', 'performance_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_records');
    }
};
