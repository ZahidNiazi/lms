<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_completed',
        'status',
        'application_stage',
        'is_reachable',
        'is_under_age_18',
        'application_date',
        'rejection_reason'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'profile_completed' => 'boolean',
        'is_reachable' => 'boolean',
        'is_under_age_18' => 'boolean',
        'application_date' => 'date',
    ];

    public function profile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function parentDetail()
    {
        return $this->hasOne(ParentDetail::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(StudentTrainingEnrollment::class);
    }

    public function deployments()
    {
        return $this->hasMany(Deployment::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function certificates()
    {
        return $this->hasMany(StudentCertificate::class);
    }

    public function applicationStatus()
    {
        return $this->hasOne(ApplicationStatus::class);
    }

    public function jobPortalApplication()
    {
        return $this->hasOne(JobPortalApplication::class);
    }

    // Helper methods
    public function isProfileComplete()
    {
        return $this->profile_completed;
    }

    public function getCurrentTrainingStatus()
    {
        $enrollment = $this->trainingEnrollments()
            ->whereHas('trainingBatch', function($query) {
                $query->where('status', 'active');
            })
            ->latest()
            ->first();

        return $enrollment ? $enrollment->status : null;
    }

    public function getCurrentDeployment()
    {
        return $this->deployments()
            ->where('status', 'active')
            ->latest()
            ->first();
    }
}
