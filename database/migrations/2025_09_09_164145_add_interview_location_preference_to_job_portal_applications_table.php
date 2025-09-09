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
        Schema::table('job_portal_applications', function (Blueprint $table) {
            $table->foreignId('preferred_interview_location_id')->nullable()->constrained('interview_locations')->onDelete('set null')->after('contact_attempts_count');
            $table->text('location_preference_reason')->nullable()->after('preferred_interview_location_id');
            $table->timestamp('location_preference_submitted_at')->nullable()->after('location_preference_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_portal_applications', function (Blueprint $table) {
            $table->dropForeign(['preferred_interview_location_id']);
            $table->dropColumn([
                'preferred_interview_location_id',
                'location_preference_reason',
                'location_preference_submitted_at'
            ]);
        });
    }
};
