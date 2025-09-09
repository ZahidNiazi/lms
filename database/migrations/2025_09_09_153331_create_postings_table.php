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
        Schema::create('postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_student_id')->constrained('sms_students')->onDelete('cascade');
            $table->enum('posting_type', ['police', 'mndf', 'other_units']);
            $table->string('unit_name'); // Police, MNDF, CDSS, EME, ME, etc.
            $table->string('position')->nullable();
            $table->date('posting_date');
            $table->text('posting_remarks')->nullable();
            $table->string('status')->default('active'); // active, transferred, completed
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['posting_type', 'unit_name']);
            $table->index(['posting_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postings');
    }
};
