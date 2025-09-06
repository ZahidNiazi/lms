<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'trigger_event',
        'subject',
        'body',
        'variables',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    public function renderTemplate(array $data): array
    {
        $subject = $this->subject;
        $body = $this->body;
        
        // Replace variables in subject and body
        foreach ($data as $key => $value) {
            $placeholder = "{{$key}}";
            $subject = str_replace($placeholder, $value, $subject);
            $body = str_replace($placeholder, $value, $body);
        }
        
        return [
            'subject' => $subject,
            'body' => $body
        ];
    }

    public function validateVariables(array $data): array
    {
        $requiredVariables = $this->getAvailableVariables();
        $missing = [];
        
        foreach ($requiredVariables as $variable) {
            if (!isset($data[$variable])) {
                $missing[] = $variable;
            }
        }
        
        return $missing;
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTrigger($query, $triggerEvent)
    {
        return $query->where('trigger_event', $triggerEvent);
    }

    public function scopeByTypeAndTrigger($query, $type, $triggerEvent)
    {
        return $query->where('type', $type)
                    ->where('trigger_event', $triggerEvent)
                    ->where('is_active', true);
    }
}