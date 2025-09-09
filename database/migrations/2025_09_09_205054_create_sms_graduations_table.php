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
        Schema::create('sms_graduations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->date('graduation_date');
            $table->string('final_grade')->nullable();
            $table->enum('graduation_status', ['graduated', 'failed', 'pending'])->default('pending');
            $table->enum('posting_status', ['pending', 'posted', 'completed'])->default('pending');
            $table->string('posting_location')->nullable();
            $table->string('posting_unit')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('graduated_by')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
            $table->foreign('graduated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_graduations');
    }
};
