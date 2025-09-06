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
        Schema::create('application_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->enum('type', ['email', 'sms', 'whatsapp', 'system']);
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'failed', 'pending'])->default('pending');
            $table->json('recipient_info')->nullable(); // Store recipient details
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('student_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('student_response')->nullable();
            $table->foreignId('sent_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['application_id', 'type'], 'app_type_idx');
            $table->index(['status', 'sent_at'], 'status_sent_idx');
            $table->index(['student_acknowledged'], 'acknowledged_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_communications');
    }
};