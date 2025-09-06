<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'atoll',
        'contact_person',
        'contact_phone',
        'contact_email',
        'capacity',
        'available_facilities',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'available_facilities' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getFullAddress(): string
    {
        $address = $this->address;
        
        if ($this->city) {
            $address .= ', ' . $this->city;
        }
        
        if ($this->atoll) {
            $address .= ', ' . $this->atoll;
        }
        
        return $address;
    }

    public function getContactInfo(): string
    {
        $info = [];
        
        if ($this->contact_person) {
            $info[] = 'Contact: ' . $this->contact_person;
        }
        
        if ($this->contact_phone) {
            $info[] = 'Phone: ' . $this->contact_phone;
        }
        
        if ($this->contact_email) {
            $info[] = 'Email: ' . $this->contact_email;
        }
        
        return implode(' | ', $info);
    }

    public function getAvailableFacilities(): array
    {
        return $this->available_facilities ?? [];
    }

    public function hasFacility(string $facility): bool
    {
        $facilities = $this->getAvailableFacilities();
        return in_array($facility, $facilities);
    }

    public function addFacility(string $facility): void
    {
        $facilities = $this->getAvailableFacilities();
        
        if (!in_array($facility, $facilities)) {
            $facilities[] = $facility;
            $this->available_facilities = $facilities;
            $this->save();
        }
    }

    public function removeFacility(string $facility): void
    {
        $facilities = $this->getAvailableFacilities();
        $facilities = array_filter($facilities, function($f) use ($facility) {
            return $f !== $facility;
        });
        
        $this->available_facilities = array_values($facilities);
        $this->save();
    }

    public function getFacilitiesList(): string
    {
        $facilities = $this->getAvailableFacilities();
        return implode(', ', $facilities);
    }

    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByAtoll($query, $atoll)
    {
        return $query->where('atoll', $atoll);
    }

    public function scopeWithCapacity($query, $minCapacity = 1)
    {
        return $query->where('capacity', '>=', $minCapacity);
    }

    public function scopeWithFacility($query, $facility)
    {
        return $query->whereJsonContains('available_facilities', $facility);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('address', 'like', "%{$searchTerm}%")
              ->orWhere('city', 'like', "%{$searchTerm}%")
              ->orWhere('atoll', 'like', "%{$searchTerm}%");
        });
    }
}