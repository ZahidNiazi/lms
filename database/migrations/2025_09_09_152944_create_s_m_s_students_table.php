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
            $table->foreignId('job_portal_application_id')->constrained('job_portal_applications')->onDelete('cascade');
            $table->string('student_id')->unique(); // SMS Student ID
            $table->string('rank')->nullable();
            $table->string('photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name_in_dhivehi')->nullable();
            $table->string('email');
            $table->string('national_id', 20); // A123456 format
            $table->string('contact_no', 20);
            $table->enum('gender', ['male', 'female']);
            $table->string('blood_group', 10)->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->integer('service_duration')->nullable(); // in months
            $table->string('parent_name')->nullable();
            $table->string('parent_relationship')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_contact_no', 20)->nullable();
            $table->text('parent_address')->nullable();
            $table->foreignId('batch_id')->nullable()->constrained('training_batches')->onDelete('set null');
            $table->string('company')->nullable();
            $table->string('platoon')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->date('application_date');
            $table->string('applicant_number');
            $table->decimal('pay_amount', 10, 2)->nullable();
            $table->string('current_emp_location')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('status')->default('active'); // active, inactive, terminated, graduated
            $table->timestamps();
            
            $table->index(['batch_id', 'platoon']);
            $table->index(['status', 'date_of_joining']);
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
