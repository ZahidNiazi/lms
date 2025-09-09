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
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_at');
            $table->timestamp('interview_completed_at')->nullable()->after('updated_by');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')->after('interview_completed_at');
            $table->timestamp('assigned_at')->nullable()->after('assigned_by');
            $table->string('assignment_type')->nullable()->after('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_portal_applications', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['assigned_by']);
            $table->dropColumn(['updated_by', 'interview_completed_at', 'assigned_by', 'assigned_at', 'assignment_type']);
        });
    }
};