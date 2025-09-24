<?php

namespace App\Models\SMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Performance extends Model
{
    use HasFactory;

    protected $table = 'sms_performances';

    protected $fillable = [
        'student_id',
        'performance_field_id',
        'score',
        'max_score',
        'comments',
        'document_path',
        'evaluated_by',
        'evaluation_date',
        'counselling_notes',
        'pay_step',
        'performance_indicator',
        'observation_notes'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'evaluation_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function performanceField(): BelongsTo
    {
        return $this->belongsTo(PerformanceField::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PerformanceDocument::class);
    }

    // Accessors
    public function getPercentageAttribute(): float
    {
        if ($this->max_score > 0) {
            return round(($this->score / $this->max_score) * 100, 2);
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
}

