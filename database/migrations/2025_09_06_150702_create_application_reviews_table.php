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
        Schema::create('application_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->enum('review_type', [
                'document_verification',
                'basic_criteria_check',
                'final_approval',
                'rejection'
            ]);
            $table->enum('status', ['pending', 'approved', 'rejected', 'needs_resubmission']);
            $table->text('comments')->nullable();
            $table->json('document_issues')->nullable(); // Store specific document issues
            $table->json('missing_documents')->nullable(); // Store missing document types
            $table->boolean('requires_resubmission')->default(false);
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            $table->index(['application_id', 'review_type'], 'app_review_type_idx');
            $table->index(['status', 'reviewed_at'], 'status_reviewed_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_reviews');
    }
};