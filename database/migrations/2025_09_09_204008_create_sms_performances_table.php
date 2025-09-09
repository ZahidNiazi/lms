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
        Schema::create('sms_performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('performance_field_id');
            $table->decimal('score', 5, 2)->default(0);
            $table->decimal('max_score', 5, 2)->default(100);
            $table->text('comments')->nullable();
            $table->string('document_path')->nullable();
            $table->unsignedBigInteger('evaluated_by')->nullable();
            $table->date('evaluation_date');
            $table->text('counselling_notes')->nullable();
            $table->string('pay_step')->nullable();
            $table->string('performance_indicator')->nullable();
            $table->text('observation_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
            $table->foreign('performance_field_id')->references('id')->on('sms_performance_fields')->onDelete('cascade');
            $table->foreign('evaluated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_performances');
    }
};
