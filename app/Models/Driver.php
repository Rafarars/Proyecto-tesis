<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        // Información personal
        'employee_code',
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'birth_date',
        'gender',

        // Información de contacto
        'phone',
        'phone_emergency',
        'email',
        'address',

        // Información laboral
        'hire_date',
        'status',
        'shift',
        'salary',

        // Licencias y certificaciones
        'license_number',
        'license_type',
        'license_expiry',
        'has_hazmat_cert',
        'hazmat_expiry',

        // Información médica y seguridad
        'medical_exam_date',
        'medical_exam_expiry',
        'medical_restrictions',
        'emergency_contact',

        // Auditoría
        'notes',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'license_expiry' => 'date',
        'has_hazmat_cert' => 'boolean',
        'hazmat_expiry' => 'date',
        'medical_exam_date' => 'date',
        'medical_exam_expiry' => 'date',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function currentVehicle()
    {
        return $this->vehicles()->where('status', 'activo')->first();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'activo')->where('is_active', true);
    }

    public function scopeByShift($query, string $shift)
    {
        return $query->where('shift', $shift);
    }

    public function scopeWithExpiredLicense($query)
    {
        return $query->where('license_expiry', '<=', now());
    }

    public function scopeWithExpiredMedical($query)
    {
        return $query->where('medical_exam_expiry', '<=', now());
    }

    // Métodos helper
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function isLicenseExpired(): bool
    {
        return $this->license_expiry <= now();
    }

    public function isMedicalExpired(): bool
    {
        return $this->medical_exam_expiry && $this->medical_exam_expiry <= now();
    }

    public function getLicenseStatusAttribute(): string
    {
        $daysUntilExpiry = now()->diffInDays($this->license_expiry, false);

        if ($daysUntilExpiry < 0) {
            return 'vencida';
        } elseif ($daysUntilExpiry <= 30) {
            return 'por_vencer';
        } else {
            return 'vigente';
        }
    }

    public function getMedicalStatusAttribute(): string
    {
        if (!$this->medical_exam_expiry) {
            return 'sin_examen';
        }

        $daysUntilExpiry = now()->diffInDays($this->medical_exam_expiry, false);

        if ($daysUntilExpiry < 0) {
            return 'vencido';
        } elseif ($daysUntilExpiry <= 30) {
            return 'por_vencer';
        } else {
            return 'vigente';
        }
    }
}
