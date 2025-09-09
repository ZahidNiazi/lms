<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VettingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'vetting_type',
        'status',
        'police_remarks',
        'dis_remarks',
        'police_submitted_date',
        'dis_submitted_date',
        'police_cleared_date',
        'dis_cleared_date',
        'police_cleared',
        'dis_cleared',
        'processed_by'
    ];

    protected $casts = [
        'police_submitted_date' => 'date',
        'dis_submitted_date' => 'date',
        'police_cleared_date' => 'date',
        'dis_cleared_date' => 'date',
        'police_cleared' => 'boolean',
        'dis_cleared' => 'boolean'
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        if ($this->vetting_type === 'police') {
            return $this->police_cleared;
        } elseif ($this->vetting_type === 'dis') {
            return $this->dis_cleared;
        } else {
            return $this->police_cleared && $this->dis_cleared;
        }
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function markPoliceCleared()
    {
        $this->update([
            'police_cleared' => true,
            'police_cleared_date' => now()->toDateString(),
            'status' => $this->vetting_type === 'police' ? 'completed' : ($this->dis_cleared ? 'completed' : 'in_progress')
        ]);
    }

    public function markDisCleared()
    {
        $this->update([
            'dis_cleared' => true,
            'dis_cleared_date' => now()->toDateString(),
            'status' => $this->vetting_type === 'dis' ? 'completed' : ($this->police_cleared ? 'completed' : 'in_progress')
        ]);
    }
}
