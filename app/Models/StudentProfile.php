<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'first_name', 'last_name', 'nid', 'mobile_no', 'dob', 'profile_picture'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function age()
    {
        $dob = $this->dob;
        $age = Carbon::parse($dob)->age;
        return $age;
    }

    public function needsParentConsent()
    {
        return $this->age() < 18;
    }
}
