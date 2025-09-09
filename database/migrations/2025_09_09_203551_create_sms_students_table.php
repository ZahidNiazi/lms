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
        Schema::create('sms_students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('rank')->nullable();
            $table->string('photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name_in_dhivehi')->nullable();
            $table->string('email')->unique();
            $table->string('national_id')->unique();
            
            // Permanent Address
            $table->string('permanent_address_name')->nullable();
            $table->string('permanent_atoll')->nullable();
            $table->string('permanent_island')->nullable();
            $table->string('permanent_district')->nullable();
            $table->string('permanent_road_name')->nullable();
            
            // Present Address
            $table->string('present_address_name')->nullable();
            $table->string('present_atoll')->nullable();
            $table->string('present_island')->nullable();
            $table->string('present_district')->nullable();
            $table->string('present_road_name')->nullable();
            
            $table->string('contact_no');
            $table->enum('gender', ['male', 'female']);
            $table->string('blood_group')->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->integer('service_duration')->nullable();
            
            // Parent Details
            $table->string('parent_name')->nullable();
            $table->string('parent_relationship')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_contact_no')->nullable();
            $table->text('parent_address')->nullable();
            
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('company')->nullable();
            $table->string('platoon')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('application_date')->nullable();
            $table->string('applicant_number')->nullable();
            $table->decimal('pay_amount', 10, 2)->nullable();
            $table->string('status')->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key will be added later after training_batches table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_students');
    }
};
