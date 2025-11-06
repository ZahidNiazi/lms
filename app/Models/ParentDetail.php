<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'name', 'relation', 'atoll', 'island', 'address', 'mobile_no', 'email','parent_atoll_id','parent_island_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parentAtoll()
    {
        return $this->belongsTo(Atoll::class, 'parent_atoll_id');
    }

    public function parentIsland()
    {
        return $this->belongsTo(Island::class, 'parent_island_id');
    }
}
