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
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_student_id')->constrained('sms_students')->onDelete('cascade');
            $table->date('graduation_date');
            $table->string('certificate_number')->unique();
            $table->text('graduation_remarks')->nullable();
            $table->boolean('is_under_18')->default(false);
            $table->string('posting_status')->default('pending'); // pending, posted_to_police, posted_to_mndf, posted_to_other_units
            $table->foreignId('graduated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['graduation_date', 'posting_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduations');
    }
};
