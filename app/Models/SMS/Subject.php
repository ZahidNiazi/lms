<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'sms_subjects';

    protected $fillable = [
        'name',
        'code',
        'description',
        'credits',
        'is_active'
    ];

    protected $casts = [
        'credits' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

