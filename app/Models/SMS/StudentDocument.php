<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    use HasFactory;

    protected $table = 'sms_student_documents';

    protected $fillable = [
        'student_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'upload_date',
        'is_verified',
        'verified_by',
        'verified_at',
        'remarks'
    ];

    protected $casts = [
        'upload_date' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'file_size' => 'integer'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    // Accessors
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'nid' => 'National ID',
            'photo' => 'Photo',
            'medical' => 'Medical Documents',
            'police_report' => 'Police Report',
            'parent_consent' => 'Parent Consent',
            'job_agreement' => 'Job Agreement',
            'academic' => 'Academic Documents',
            'olevel' => 'O-Level',
            'alevel' => 'A-Level',
            'other' => 'Other',
            default => ucfirst(str_replace('_', ' ', $this->document_type))
        };
    }
}

