<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobPortalApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'application_number',
        'status',
        'rejection_reason',
        'remarks',
        'documents_verified',
        'basic_criteria_met',
        'reviewed_by',
        'reviewed_at',
        'batch_id',
        'batch_position',
        'is_reserve',
        'updated_by',
        'interview_completed_at',
        'assigned_by',
        'assigned_at',
        'assignment_type',
        'is_second_attempt',
        'is_application_closed',
        'application_closure_reason',
        'application_closed_at',
        'application_closed_by',
        'is_unreachable',
        'last_contact_attempt',
        'contact_attempts_count',
        'preferred_interview_location_id',
        'location_preference_reason',
        'location_preference_submitted_at'
    ];

    protected $casts = [
        'documents_verified' => 'boolean',
        'basic_criteria_met' => 'boolean',
        'is_reserve' => 'boolean',
        'reviewed_at' => 'datetime',
        'is_second_attempt' => 'boolean',
        'is_application_closed' => 'boolean',
        'application_closed_at' => 'datetime',
        'is_unreachable' => 'boolean',
        'last_contact_attempt' => 'datetime',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(TrainingBatch::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ApplicationReview::class, 'application_id');
    }

    public function interviewSchedules(): HasMany
    {
        return $this->hasMany(JobPortalInterviewSchedule::class, 'application_id');
    }

    public function interviewResults(): HasMany
    {
        return $this->hasMany(InterviewResult::class, 'application_id');
    }

    public function communications(): HasMany
    {
        return $this->hasMany(ApplicationCommunication::class, 'application_id');
    }

    public function vettings(): HasMany
    {
        return $this->hasMany(Vetting::class, 'application_id');
    }

    public function vetting(): HasMany
    {
        return $this->hasMany(Vetting::class, 'application_id');
    }

    public function nominationLetter(): HasOne
    {
        return $this->hasOne(NominationLetter::class);
    }

    public function serviceOfferLetter(): HasOne
    {
        return $this->hasOne(ServiceOfferLetter::class);
    }

    public function vettingRecord(): HasOne
    {
        return $this->hasOne(VettingRecord::class);
    }

    public function preferredInterviewLocation(): BelongsTo
    {
        return $this->belongsTo(InterviewLocation::class, 'preferred_interview_location_id');
    }

    // Helper methods
    public function generateApplicationNumber(): string
    {
        $year = date('Y');
        $lastApplication = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastApplication ? 
            (int)substr($lastApplication->application_number, -4) + 1 : 1;
        
        return "NS-{$year}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function needsResubmission(): bool
    {
        return $this->reviews()
            ->where('requires_resubmission', true)
            ->where('status', 'needs_resubmission')
            ->exists();
    }

    // Scope for querying applications that need resubmission
    public function scopeNeedsResubmission($query)
    {
        return $query->whereHas('reviews', function($q) {
            $q->where('requires_resubmission', true)
              ->where('status', 'needs_resubmission');
        });
    }

    // Alternative scope method to avoid conflicts
    public function scopeRequiresResubmission($query)
    {
        return $query->whereHas('reviews', function($q) {
            $q->where('requires_resubmission', true)
              ->where('status', 'needs_resubmission');
        });
    }

    public function getLatestReview()
    {
        return $this->reviews()->latest()->first();
    }

    public function getDocumentIssues()
    {
        $review = $this->reviews()
            ->where('review_type', 'document_verification')
            ->latest()
            ->first();
        
        return $review ? $review->document_issues : null;
    }

    public function getMissingDocuments()
    {
        $review = $this->reviews()
            ->where('review_type', 'document_verification')
            ->latest()
            ->first();
        
        return $review ? $review->missing_documents : null;
    }

    // Scopes
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeDocumentReview($query)
    {
        return $query->where('status', 'document_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}