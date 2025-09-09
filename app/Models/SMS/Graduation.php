<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Graduation extends Model
{
    use HasFactory;

    protected $table = 'sms_graduations';

    protected $fillable = [
        'student_id',
        'graduation_date',
        'final_grade',
        'graduation_status',
        'posting_status',
        'posting_location',
        'posting_unit',
        'remarks',
        'graduated_by',
        'document_path'
    ];

    protected $casts = [
        'graduation_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function graduator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graduated_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('graduation_status', $status);
    }

    public function scopeByPostingStatus($query, $status)
    {
        return $query->where('posting_status', $status);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->graduation_status) {
            'graduated' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'secondary'
        };
    }
}

