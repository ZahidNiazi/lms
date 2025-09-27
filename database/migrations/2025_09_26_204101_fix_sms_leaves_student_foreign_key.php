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
        Schema::table('sms_leaves', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['student_id']);

            // Add new foreign key constraint to reference the main students table
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_leaves', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['student_id']);

            // Restore the original foreign key constraint
            $table->foreign('student_id')->references('id')->on('sms_students')->onDelete('cascade');
        });
    }
};
