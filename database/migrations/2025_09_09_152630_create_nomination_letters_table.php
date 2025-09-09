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
        Schema::create('nomination_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->string('letter_number')->unique();
            $table->date('nomination_date');
            $table->text('content');
            $table->string('status')->default('draft'); // draft, sent, acknowledged
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomination_letters');
    }
};
