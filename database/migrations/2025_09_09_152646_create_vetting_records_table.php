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
        Schema::create('vetting_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->enum('vetting_type', ['police', 'dis', 'both']);
            $table->string('status')->default('pending'); // pending, in_progress, completed, failed
            $table->text('police_remarks')->nullable();
            $table->text('dis_remarks')->nullable();
            $table->date('police_submitted_date')->nullable();
            $table->date('dis_submitted_date')->nullable();
            $table->date('police_cleared_date')->nullable();
            $table->date('dis_cleared_date')->nullable();
            $table->boolean('police_cleared')->default(false);
            $table->boolean('dis_cleared')->default(false);
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vetting_records');
    }
};
