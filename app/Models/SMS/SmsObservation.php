<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsObservation extends Model
{
    use HasFactory;

    protected $table = 'sms_observations';

    protected $fillable = [
        'student_id',
        'observation_type',
        'severity_level',
        'observation_notes',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }
}
