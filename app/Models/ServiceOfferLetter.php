<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOfferLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'letter_number',
        'offer_date',
        'joining_date',
        'content',
        'joining_instructions',
        'status',
        'sent_at',
        'acknowledged_at',
        'accepted_at',
        'created_by'
    ];

    protected $casts = [
        'offer_date' => 'date',
        'joining_date' => 'date',
        'sent_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'accepted_at' => 'datetime'
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function isSent(): bool
    {
        return $this->status === 'sent' && $this->sent_at !== null;
    }

    public function isAcknowledged(): bool
    {
        return $this->status === 'acknowledged' && $this->acknowledged_at !== null;
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted' && $this->accepted_at !== null;
    }

    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    public function markAsAcknowledged()
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now()
        ]);
    }

    public function markAsAccepted()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
    }
}
