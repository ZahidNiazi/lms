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
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->string('trigger_event')->nullable()->after('type');
            $table->string('subject')->nullable()->after('trigger_event');
            $table->text('body')->nullable()->after('subject');
            $table->json('variables')->nullable()->after('body');
            $table->boolean('is_active')->default(true)->after('variables');
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'type',
                'trigger_event', 
                'subject',
                'body',
                'variables',
                'is_active',
                'created_by'
            ]);
        });
    }
};
