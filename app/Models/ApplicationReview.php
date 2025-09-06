<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'reviewer_id',
        'review_type',
        'status',
        'comments',
        'document_issues',
        'missing_documents',
        'requires_resubmission',
        'reviewed_at'
    ];

    protected $casts = [
        'document_issues' => 'array',
        'missing_documents' => 'array',
        'requires_resubmission' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class, 'application_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Helper methods
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
        return $this->requires_resubmission && $this->status === 'needs_resubmission';
    }

    public function hasDocumentIssues(): bool
    {
        return !empty($this->document_issues);
    }

    public function hasMissingDocuments(): bool
    {
        return !empty($this->missing_documents);
    }

    public function getDocumentIssuesList(): array
    {
        return $this->document_issues ?? [];
    }

    public function getMissingDocumentsList(): array
    {
        return $this->missing_documents ?? [];
    }

    // Scopes
    public function scopeDocumentVerification($query)
    {
        return $query->where('review_type', 'document_verification');
    }

    public function scopeBasicCriteriaCheck($query)
    {
        return $query->where('review_type', 'basic_criteria_check');
    }

    public function scopeFinalApproval($query)
    {
        return $query->where('review_type', 'final_approval');
    }

    public function scopeRejection($query)
    {
        return $query->where('review_type', 'rejection');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeNeedsResubmission($query)
    {
        return $query->where('requires_resubmission', true)
                    ->where('status', 'needs_resubmission');
    }
}