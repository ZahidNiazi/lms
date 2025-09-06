<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoliceDisVetting extends Model
{
    use HasFactory;

    protected $table = 'police_dis_vetting';

    protected $fillable = [
        'application_id',
        'vetting_type',
        'status',
        'reference_number',
        'comments',
        'submitted_date',
        'completed_date',
        'processed_by'
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'completed_date' => 'date',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class, 'application_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helper methods
    public function isPolice(): bool
    {
        return $this->vetting_type === 'police';
    }

    public function isDis(): bool
    {
        return $this->vetting_type === 'dis';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isCleared(): bool
    {
        return $this->status === 'cleared';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['cleared', 'failed', 'rejected']);
    }

    public function getProcessingTime(): ?int
    {
        if ($this->submitted_date && $this->completed_date) {
            return $this->submitted_date->diffInDays($this->completed_date);
        }
        
        return null;
    }

    public function getStatusDisplayName(): string
    {
        $statuses = [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'cleared' => 'Cleared',
            'failed' => 'Failed',
            'rejected' => 'Rejected'
        ];
        
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getVettingTypeDisplayName(): string
    {
        return $this->vetting_type === 'police' ? 'Police Vetting' : 'DIS Vetting';
    }

    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsCleared(string $referenceNumber = null, string $comments = null): void
    {
        $this->update([
            'status' => 'cleared',
            'reference_number' => $referenceNumber,
            'comments' => $comments,
            'completed_date' => now()->toDateString()
        ]);
    }

    public function markAsFailed(string $comments = null): void
    {
        $this->update([
            'status' => 'failed',
            'comments' => $comments,
            'completed_date' => now()->toDateString()
        ]);
    }

    public function markAsRejected(string $comments = null): void
    {
        $this->update([
            'status' => 'rejected',
            'comments' => $comments,
            'completed_date' => now()->toDateString()
        ]);
    }

    // Scopes
    public function scopePolice($query)
    {
        return $query->where('vetting_type', 'police');
    }

    public function scopeDis($query)
    {
        return $query->where('vetting_type', 'dis');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCleared($query)
    {
        return $query->where('status', 'cleared');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['cleared', 'failed', 'rejected']);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('submitted_date', [$startDate, $endDate]);
    }
}