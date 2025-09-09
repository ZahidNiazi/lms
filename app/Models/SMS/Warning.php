<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    use HasFactory;

    protected $table = 'sms_warnings';

    protected $fillable = [
        'student_id',
        'warning_type',
        'warning_reason',
        'description',
        'warning_date',
        'issued_by',
        'severity_level',
        'document_path',
        'remarks',
        'is_active'
    ];

    protected $casts = [
        'warning_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('warning_type', $type);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity_level', $severity);
    }

    // Accessors
    public function getSeverityBadgeAttribute(): string
    {
        return match($this->severity_level) {
            'low' => 'info',
            'medium' => 'warning',
            'high' => 'danger',
            'critical' => 'dark',
            default => 'secondary'
        };
    }
}

