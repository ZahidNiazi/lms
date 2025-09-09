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
        Schema::create('sms_postings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->enum('posting_type', ['police', 'mndf', 'other_units']);
            $table->string('posting_location');
            $table->string('posting_unit');
            $table->date('posting_date');
            $table->date('effective_date');
            $table->enum('status', ['active', 'completed', 'cancelled', 'pending'])->default('pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_postings');
    }
};
