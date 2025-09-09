<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'sms_medical_records';

    protected $fillable = [
        'student_id',
        'current_medical_status',
        'medical_excuses',
        'document_path',
        'remarks',
        'recorded_by',
        'record_date'
    ];

    protected $casts = [
        'record_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(MedicalDocument::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('current_medical_status', $status);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('record_date', '>=', now()->subDays($days));
    }
}

