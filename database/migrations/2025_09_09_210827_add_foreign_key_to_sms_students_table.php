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
        Schema::table('sms_students', function (Blueprint $table) {
            $table->foreign('batch_id')->references('id')->on('sms_training_batches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_students', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
        });
    }
};
