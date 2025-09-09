<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingBatch extends Model
{
    use HasFactory;

    protected $table = 'sms_training_batches';

    protected $fillable = [
        'batch_name',
        'batch_code',
        'start_date',
        'end_date',
        'capacity',
        'current_students',
        'status',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'capacity' => 'integer',
        'current_students' => 'integer'
    ];

    // Relationships
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'batch_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'completed' => 'info',
            'upcoming' => 'warning',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getAvailableSlotsAttribute(): int
    {
        return $this->capacity - $this->current_students;
    }

    public function getDurationAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date);
        }
        return 0;
    }
}

