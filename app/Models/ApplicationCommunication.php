<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'type',
        'subject',
        'message',
        'status',
        'recipient_info',
        'sent_at',
        'delivered_at',
        'error_message',
        'student_acknowledged',
        'acknowledged_at',
        'student_response',
        'sent_by'
    ];

    protected $casts = [
        'recipient_info' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'student_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class, 'application_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    // Helper methods
    public function isEmail(): bool
    {
        return $this->type === 'email';
    }

    public function isSMS(): bool
    {
        return $this->type === 'sms';
    }

    public function isWhatsApp(): bool
    {
        return $this->type === 'whatsapp';
    }

    public function isSystem(): bool
    {
        return $this->type === 'system';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAcknowledged(): bool
    {
        return $this->student_acknowledged;
    }

    public function getRecipientInfo(): array
    {
        return $this->recipient_info ?? [];
    }

    public function getRecipientEmail(): ?string
    {
        $info = $this->getRecipientInfo();
        return $info['email'] ?? null;
    }

    public function getRecipientPhone(): ?string
    {
        $info = $this->getRecipientInfo();
        return $info['phone'] ?? null;
    }

    public function getRecipientName(): ?string
    {
        $info = $this->getRecipientInfo();
        return $info['name'] ?? null;
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage
        ]);
    }

    public function acknowledge(string $response = null): void
    {
        $this->update([
            'student_acknowledged' => true,
            'acknowledged_at' => now(),
            'student_response' => $response
        ]);
    }

    public function getDeliveryTime(): ?int
    {
        if ($this->sent_at && $this->delivered_at) {
            return $this->delivered_at->diffInSeconds($this->sent_at);
        }
        
        return null;
    }

    public function getAcknowledgmentTime(): ?int
    {
        if ($this->delivered_at && $this->acknowledged_at) {
            return $this->acknowledged_at->diffInSeconds($this->delivered_at);
        }
        
        return null;
    }

    // Scopes
    public function scopeEmail($query)
    {
        return $query->where('type', 'email');
    }

    public function scopeSMS($query)
    {
        return $query->where('type', 'sms');
    }

    public function scopeWhatsApp($query)
    {
        return $query->where('type', 'whatsapp');
    }

    public function scopeSystem($query)
    {
        return $query->where('type', 'system');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('student_acknowledged', true);
    }

    public function scopeNotAcknowledged($query)
    {
        return $query->where('student_acknowledged', false);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}