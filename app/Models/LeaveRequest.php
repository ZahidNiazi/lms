<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sms_student_id',
        'leave_type',
        'applied_on',
        'start_date',
        'end_date',
        'total_days',
        'leave_reasons',
        'status',
        'admin_remarks',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'applied_on' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function smsStudent(): BelongsTo
    {
        return $this->belongsTo(SMSStudent::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
