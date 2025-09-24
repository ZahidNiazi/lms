<?php

namespace App\Models\SMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceDocument extends Model
{
    use HasFactory;

    protected $table = 'sms_performance_documents';

    protected $fillable = [
        'performance_id',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'upload_date'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'file_size' => 'integer'
    ];

    // Relationships
    public function performance(): BelongsTo
    {
        return $this->belongsTo(Performance::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

