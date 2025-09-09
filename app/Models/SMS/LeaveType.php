<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'sms_leave_types';

    protected $fillable = [
        'name',
        'description',
        'max_days_per_year',
        'requires_approval',
        'is_active'
    ];

    protected $casts = [
        'max_days_per_year' => 'integer',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

