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
        Schema::table('job_portal_interview_schedules', function (Blueprint $table) {
            $table->string('interview_type')->nullable()->after('interview_time');
            $table->text('instructions')->nullable()->after('interview_type');
            $table->foreignId('scheduled_by')->nullable()->constrained('users')->onDelete('set null')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_portal_interview_schedules', function (Blueprint $table) {
            $table->dropForeign(['scheduled_by']);
            $table->dropColumn(['interview_type', 'instructions', 'scheduled_by']);
        });
    }
};