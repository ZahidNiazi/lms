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
        Schema::create('sms_training_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_name');
            $table->string('batch_code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('capacity');
            $table->integer('current_students')->default(0);
            $table->enum('status', ['upcoming', 'active', 'completed', 'cancelled'])->default('upcoming');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_training_batches');
    }
};
