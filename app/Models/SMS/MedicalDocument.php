<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalDocument extends Model
{
    use HasFactory;

    protected $table = 'sms_medical_documents';

    protected $fillable = [
        'medical_record_id',
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
    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

