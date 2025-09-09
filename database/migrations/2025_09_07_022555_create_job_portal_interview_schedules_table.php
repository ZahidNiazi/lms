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
        Schema::create('job_portal_interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('interview_locations')->onDelete('cascade');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->string('venue');
            $table->text('dress_code')->nullable();
            $table->text('travel_arrangements')->nullable();
            $table->text('accommodation_arrangements')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->boolean('student_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['interview_date', 'interview_time'], 'interview_datetime_idx');
            $table->index(['status', 'student_acknowledged'], 'status_acknowledged_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_portal_interview_schedules');
    }
};