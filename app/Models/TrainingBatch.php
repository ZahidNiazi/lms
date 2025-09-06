<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_name',
        'batch_code',
        'start_date',
        'end_date',
        'status',
        'capacity',
        'enrolled_count',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function enrollments()
    {
        return $this->hasMany(StudentTrainingEnrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_training_enrollments');
    }

    public function applications()
    {
        return $this->hasMany(JobPortalApplication::class, 'batch_id');
    }
}
