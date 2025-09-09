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
        Schema::create('vettings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('type'); // police, dis, medical, background
            $table->string('status')->default('pending'); // pending, in_progress, completed, failed
            $table->text('notes')->nullable();
            $table->date('due_date')->nullable();
            $table->text('result')->nullable();
            $table->unsignedBigInteger('initiated_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('job_portal_applications')->onDelete('cascade');
            $table->foreign('initiated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['application_id', 'type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vettings');
    }
};
