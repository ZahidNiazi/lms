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
            $table->boolean('is_second_attempt')->default(false)->after('assignment_type');
            $table->boolean('is_application_closed')->default(false)->after('is_second_attempt');
            $table->text('application_closure_reason')->nullable()->after('is_application_closed');
            $table->timestamp('application_closed_at')->nullable()->after('application_closure_reason');
            $table->foreignId('application_closed_by')->nullable()->constrained('users')->onDelete('set null')->after('application_closed_at');
            $table->boolean('is_unreachable')->default(false)->after('application_closed_by');
            $table->timestamp('last_contact_attempt')->nullable()->after('is_unreachable');
            $table->integer('contact_attempts_count')->default(0)->after('last_contact_attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_portal_applications', function (Blueprint $table) {
            $table->dropColumn([
                'is_second_attempt',
                'is_application_closed',
                'application_closure_reason',
                'application_closed_at',
                'application_closed_by',
                'is_unreachable',
                'last_contact_attempt',
                'contact_attempts_count'
            ]);
        });
    }
};
