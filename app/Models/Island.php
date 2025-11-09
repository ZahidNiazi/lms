<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
    use HasFactory;

    protected $fillable = [
        'atoll_id',
        'name',
        'created_by',
    ];

    public function atoll()
    {
        return $this->belongsTo(Atoll::class);
    }
}
