<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    protected $fillable = [
        // Información básica
        'license_plate',
        'code',
        'brand',
        'model',
        'year',
        'color',
        'vin',

        // Especificaciones técnicas
        'type',
        'capacity_tons',
        'fuel_capacity_liters',
        'fuel_type',
        'engine',

        // Estado y operación
        'status',
        'driver_id',
        'odometer_km',
        'last_maintenance',
        'next_maintenance',

        // Ubicación y seguimiento
        'current_lat',
        'current_lng',
        'current_speed',
        'location_updated_at',

        // Información administrativa
        'purchase_date',
        'purchase_price',
        'insurance_policy',
        'insurance_expiry',
        'notes',

        // Auditoría
        'is_active',
    ];

    protected $casts = [
        'year' => 'integer',
        'capacity_tons' => 'decimal:2',
        'fuel_capacity_liters' => 'decimal:2',
        'odometer_km' => 'decimal:2',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
        'current_speed' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'last_maintenance' => 'date',
        'next_maintenance' => 'date',
        'purchase_date' => 'date',
        'insurance_expiry' => 'date',
        'location_updated_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function assignedRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'assigned_vehicle_id');
    }

    public function currentRoute(): HasOne
    {
        return $this->hasOne(Route::class, 'assigned_vehicle_id')
                   ->where('status', 'activa');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'activo')->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithDriver($query)
    {
        return $query->whereNotNull('driver_id');
    }

    public function scopeWithoutDriver($query)
    {
        return $query->whereNull('driver_id');
    }

    // Métodos helper
    public function updateLocation(float $latitude, float $longitude, float $speed = 0): void
    {
        $this->update([
            'current_lat' => $latitude,
            'current_lng' => $longitude,
            'current_speed' => $speed,
            'location_updated_at' => now(),
        ]);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->brand} {$this->model}";
    }

    public function getCurrentLocationAttribute(): ?array
    {
        if ($this->current_lat && $this->current_lng) {
            return [
                'lat' => (float) $this->current_lat,
                'lng' => (float) $this->current_lng,
            ];
        }
        return null;
    }

    public function isMaintenanceDue(): bool
    {
        return $this->next_maintenance && $this->next_maintenance <= now()->addDays(7);
    }

    public function getMaintenanceStatusAttribute(): string
    {
        if (!$this->next_maintenance) {
            return 'sin_programar';
        }

        $daysUntilMaintenance = now()->diffInDays($this->next_maintenance, false);

        if ($daysUntilMaintenance < 0) {
            return 'vencido';
        } elseif ($daysUntilMaintenance <= 7) {
            return 'proximo';
        } else {
            return 'al_dia';
        }
    }

    public function getLastMaintenanceRecord()
    {
        return $this->maintenanceRecords()
                   ->where('status', 'completado')
                   ->latest('completion_date')
                   ->first();
    }
}
