<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'type', 'atoll', 'island', 'district', 'address','atoll_id','island_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function atoll()
    {
        return $this->belongsTo(Atoll::class, 'atoll_id');
    }

    public function island()
    {
        return $this->belongsTo(Island::class, 'island_id');
    }
}
