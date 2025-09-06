<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interview_schedule_id',
        'stage',
        'marks',
        'max_marks',
        'result',
        'comments',
        'detailed_scores',
        'evaluator_id',
        'evaluated_at'
    ];

    protected $casts = [
        'detailed_scores' => 'array',
        'evaluated_at' => 'datetime',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(JobPortalApplication::class, 'application_id');
    }

    public function interviewSchedule(): BelongsTo
    {
        return $this->belongsTo(JobPortalInterviewSchedule::class, 'interview_schedule_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Helper methods
    public function isPassed(): bool
    {
        return $this->result === 'pass';
    }

    public function isFailed(): bool
    {
        return $this->result === 'fail';
    }

    public function isAbsent(): bool
    {
        return $this->result === 'absent';
    }

    public function isPending(): bool
    {
        return $this->result === 'pending';
    }

    public function getPercentage(): float
    {
        if ($this->max_marks == 0) {
            return 0;
        }
        
        return round(($this->marks / $this->max_marks) * 100, 2);
    }

    public function getGrade(): string
    {
        $percentage = $this->getPercentage();
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        return 'F';
    }

    public function getStageDisplayName(): string
    {
        $stages = [
            'medical' => 'Medical Examination',
            'fitness_swimming' => 'Fitness Test - Swimming',
            'fitness_run' => 'Fitness Test - Running',
            'aptitude_test' => 'Aptitude Test',
            'physical_interview' => 'Physical Interview'
        ];
        
        return $stages[$this->stage] ?? ucfirst(str_replace('_', ' ', $this->stage));
    }

    public function getDetailedScores(): array
    {
        return $this->detailed_scores ?? [];
    }

    public function setDetailedScore($category, $score, $maxScore = null): void
    {
        $scores = $this->detailed_scores ?? [];
        $scores[$category] = [
            'score' => $score,
            'max_score' => $maxScore ?? $score
        ];
        $this->detailed_scores = $scores;
        $this->save();
    }

    // Scopes
    public function scopeMedical($query)
    {
        return $query->where('stage', 'medical');
    }

    public function scopeFitnessSwimming($query)
    {
        return $query->where('stage', 'fitness_swimming');
    }

    public function scopeFitnessRun($query)
    {
        return $query->where('stage', 'fitness_run');
    }

    public function scopeAptitudeTest($query)
    {
        return $query->where('stage', 'aptitude_test');
    }

    public function scopePhysicalInterview($query)
    {
        return $query->where('stage', 'physical_interview');
    }

    public function scopePassed($query)
    {
        return $query->where('result', 'pass');
    }

    public function scopeFailed($query)
    {
        return $query->where('result', 'fail');
    }

    public function scopeAbsent($query)
    {
        return $query->where('result', 'absent');
    }

    public function scopePending($query)
    {
        return $query->where('result', 'pending');
    }

    public function scopeEvaluated($query)
    {
        return $query->whereNotNull('evaluated_at');
    }
}