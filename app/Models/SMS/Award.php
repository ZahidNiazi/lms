<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Award extends Model
{
    use HasFactory;

    protected $table = 'sms_awards';

    protected $fillable = [
        'student_id',
        'award_type',
        'award_name',
        'description',
        'award_date',
        'awarded_by',
        'document_path',
        'remarks'
    ];

    protected $casts = [
        'award_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function awarder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('award_type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('award_date', '>=', now()->subDays($days));
    }
}

