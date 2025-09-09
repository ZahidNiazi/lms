<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPortalInterviewSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'location_id',
        'interview_date',
        'interview_time',
        'interview_type',
        'instructions',
        'venue',
        'dress_code',
        'travel_arrangements',
        'accommodation_arrangements',
        'status',
        'student_acknowledged',
        'acknowledged_at',
        'notes',
        'scheduled_by'
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_time' => 'datetime:H:i',
        'student_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class, 'application_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(InterviewLocation::class, 'location_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(InterviewResult::class, 'interview_schedule_id');
    }

    // Helper methods
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function isAcknowledged(): bool
    {
        return $this->student_acknowledged;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getFormattedDateTime(): string
    {
        return $this->interview_date->format('M d, Y') . ' at ' . 
               $this->interview_time->format('h:i A');
    }

    public function getFullVenueInfo(): string
    {
        $info = $this->venue;
        
        if ($this->dress_code) {
            $info .= "\nDress Code: " . $this->dress_code;
        }
        
        if ($this->travel_arrangements) {
            $info .= "\nTravel: " . $this->travel_arrangements;
        }
        
        if ($this->accommodation_arrangements) {
            $info .= "\nAccommodation: " . $this->accommodation_arrangements;
        }
        
        return $info;
    }

    public function acknowledge($comments = null): bool
    {
        $this->update([
            'student_acknowledged' => true,
            'acknowledged_at' => now(),
            'notes' => $comments
        ]);
        
        return true;
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('student_acknowledged', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('interview_date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled');
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('interview_date', $date);
    }

    public function scopeByVenue($query, $venue)
    {
        return $query->where('venue', 'like', "%{$venue}%");
    }
}