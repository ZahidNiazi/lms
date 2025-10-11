<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atoll extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
    ];

    public function islands()
    {
        return $this->hasMany(Island::class);
    }
}
