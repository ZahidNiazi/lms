<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceField extends Model
{
    use HasFactory;

    protected $table = 'sms_performance_fields';

    protected $fillable = [
        'name',
        'description',
        'max_score',
        'is_active',
        'category'
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}

