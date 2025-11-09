<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsAcademic extends Model
{
    use HasFactory;

    protected $table = 'sms_academics';

    protected $fillable = [
        'student_id',
        'document_type',
        'institution',
        'start_date',
        'end_date',
        'result',
    ];
}
