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
        Schema::create('police_dis_vetting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->enum('vetting_type', ['police', 'dis']);
            $table->enum('status', ['pending', 'in_progress', 'cleared', 'failed', 'rejected'])->default('pending');
            $table->string('reference_number')->nullable();
            $table->text('comments')->nullable();
            $table->date('submitted_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['application_id', 'vetting_type'], 'app_vetting_type_idx');
            $table->index(['status', 'submitted_date'], 'status_submitted_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('police_dis_vetting');
    }
};