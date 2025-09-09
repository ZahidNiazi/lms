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
        Schema::create('interview_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->foreignId('interview_schedule_id')->nullable()->constrained('job_portal_interview_schedules')->onDelete('set null');
            $table->enum('stage', ['medical', 'fitness_swimming', 'fitness_run', 'aptitude_test', 'physical_interview']);
            $table->integer('marks')->nullable();
            $table->integer('max_marks')->nullable();
            $table->enum('result', ['pass', 'fail', 'absent', 'pending'])->default('pending');
            $table->text('comments')->nullable();
            $table->json('detailed_scores')->nullable();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            
            $table->index(['application_id', 'stage'], 'application_stage_idx');
            $table->index(['result', 'evaluated_at'], 'result_evaluated_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_results');
    }
};