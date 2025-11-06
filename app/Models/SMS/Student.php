<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Atoll;
use App\Models\Island;
use App\Models\TrainingBatch;
use App\Models\Company;
use App\Models\Platoon;
class Student extends Model
{
    use HasFactory;

    protected $table = 'sms_students';

    protected $fillable = [
        'student_id',
        'rank',
        'photo',
        'first_name',
        'last_name',
        'name_in_dhivehi',
        'email',
        'national_id',
        'permanent_address_name',
        'permanent_atoll_id',
        'permanent_island_id',
        'permanent_district',
        'permanent_road_name',
        'present_address_name',
        'present_atoll_id',
        'present_island_id',
        'present_district',
        'present_road_name',
        'contact_no',
        'gender',
        'blood_group',
        'date_of_birth',
        'age',
        'service_duration',
        'parent_name',
        'parent_relationship',
        'parent_email',
        'parent_contact_no',
        'parent_address',
        'parent_atoll_id',
        'parent_island_id',
        'batch_id',
        'company_id',
        'platoon_id',
        'date_of_joining',
        'application_date',
        'applicant_number',
        'pay_amount',
        'status',
        'last_login_at',
        'is_active',
        'martial_status',
        'kids'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'application_date' => 'date',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'pay_amount' => 'decimal:2'
    ];

    // Relationships
    public function batch(): BelongsTo
    {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function performances(): HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
    public function AcademiclRecords(): HasMany
    {
        return $this->hasMany(SmsAcademic::class);
    }
    public function Observation(): HasMany
    {
        return $this->hasMany(SmsObservation::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function warnings(): HasMany
    {
        return $this->hasMany(Warning::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeByCompany($query, $company)
    {
        return $query->where('company', $company);
    }

    public function scopeByPlatoon($query, $platoon)
    {
        return $query->where('platoon', $platoon);
    }

    public function permanentAtoll(): BelongsTo
    {
        return $this->belongsTo(Atoll::class, 'permanent_atoll_id');
    }

    public function permanentIsland(): BelongsTo
    {
        return $this->belongsTo(Island::class, 'permanent_island_id');
    }

    public function presentAtoll(): BelongsTo
    {
        return $this->belongsTo(Atoll::class, 'present_atoll_id');
    }

    public function presentIsland(): BelongsTo
    {
        return $this->belongsTo(Island::class, 'present_island_id');
    }

    public function parentAtoll(): BelongsTo
    {
        return $this->belongsTo(Atoll::class, 'parent_atoll_id');
    }

    public function parentIsland(): BelongsTo
    {
        return $this->belongsTo(Island::class, 'parent_island_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the platoon that the student belongs to
     */
    public function platoon()
    {
        return $this->belongsTo(Platoon::class, 'platoon_id');
    }
}

