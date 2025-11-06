<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SMSStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_portal_application_id',
        'student_id',
        'rank',
        'photo',
        'first_name',
        'last_name',
        'name_in_dhivehi',
        'email',
        'national_id',
        'contact_no',
        'gender',
        'blood_group',
        'date_of_birth',
        'age',
        'service_duration',
        'parent_name',
        'parent_relationship',
        'parent_email',
        'parent_contact_no',
        'parent_address',
        'batch_id',
        'company',
        'platoon',
        'date_of_joining',
        'application_date',
        'applicant_number',
        'pay_amount',
        'current_emp_location',
        'last_login',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'application_date' => 'date',
        'pay_amount' => 'decimal:2',
        'last_login' => 'datetime'
    ];

    // Relationships
    public function jobPortalApplication(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function performanceRecords(): HasMany
    {
        return $this->hasMany(PerformanceRecord::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function graduation(): HasMany
    {
        return $this->hasMany(Graduation::class);
    }

    public function postings(): HasMany
    {
        return $this->hasMany(Posting::class);
    }

    // Helper methods
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isGraduated(): bool
    {
        return $this->status === 'graduated';
    }

    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }

    public function getCurrentAttendanceStatus(): string
    {
        $today = now()->toDateString();
        $attendance = $this->attendanceRecords()->where('attendance_date', $today)->first();
        
        return $attendance ? $attendance->status : 'not_marked';
    }
}
