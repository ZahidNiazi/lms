<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    protected $table = 'sms_assessments';

    protected $fillable = [
        'student_id',
        'subject_id',
        'assessment_type',
        'title',
        'description',
        'total_marks',
        'obtained_marks',
        'assessment_date',
        'assessed_by',
        'remarks',
        'document_path'
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'assessment_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssessmentDocument::class);
    }

    // Accessors
    public function getPercentageAttribute(): float
    {
        if ($this->total_marks > 0) {
            return round(($this->obtained_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    public function getGradeAttribute(): string
    {
        $percentage = $this->percentage;
        
        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B+',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C+',
            $percentage >= 40 => 'C',
            $percentage >= 30 => 'D',
            default => 'F'
        };
    }

    // Scopes
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('assessment_type', $type);
    }
}

