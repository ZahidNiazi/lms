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
        Schema::create('job_portal_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('application_number')->unique();
            $table->enum('status', [
                'pending_review',
                'document_review', 
                'approved',
                'rejected',
                'interview_scheduled',
                'interview_completed',
                'selected',
                'batch_assigned',
                'training_started',
                'training_completed',
                'deployed'
            ])->default('pending_review');
            $table->text('rejection_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('documents_verified')->default(false);
            $table->boolean('basic_criteria_met')->default(false);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('batch_id')->nullable()->constrained('training_batches')->onDelete('set null');
            $table->integer('batch_position')->nullable();
            $table->boolean('is_reserve')->default(false);
            $table->timestamps();
            
            $table->index(['status', 'created_at'], 'status_created_idx');
            $table->index(['batch_id', 'batch_position'], 'batch_position_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints first (only if they exist)
        try {
            Schema::table('interview_results', function (Blueprint $table) {
                $table->dropForeign(['application_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
        
        try {
            Schema::table('job_portal_interview_schedules', function (Blueprint $table) {
                $table->dropForeign(['application_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
        
        // Then drop the table
        Schema::dropIfExists('job_portal_applications');
    }
};