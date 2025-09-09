<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentDocument extends Model
{
    use HasFactory;

    protected $table = 'sms_assessment_documents';

    protected $fillable = [
        'assessment_id',
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
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

