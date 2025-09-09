<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Posting extends Model
{
    use HasFactory;

    protected $table = 'sms_postings';

    protected $fillable = [
        'student_id',
        'posting_type',
        'posting_location',
        'posting_unit',
        'posting_date',
        'effective_date',
        'status',
        'remarks',
        'posted_by',
        'document_path'
    ];

    protected $casts = [
        'posting_date' => 'date',
        'effective_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('posting_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            'pending' => 'warning',
            default => 'secondary'
        };
    }
}

