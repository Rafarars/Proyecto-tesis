<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Route extends Model
{
    protected $fillable = [
        // Información básica
        'name',
        'code',
        'description',

        // Estado y configuración
        'status',
        'type',

        // Datos de optimización
        'collection_points',
        'optimized_sequence',
        'total_distance_km',
        'estimated_duration_minutes',

        // Asignación de vehículo
        'assigned_vehicle_id',
        'assigned_at',

        // Horarios y frecuencia
        'start_time',
        'end_time',
        'days_of_week',

        // Ubicación de inicio y fin
        'start_lat',
        'start_lng',
        'start_address',
        'end_lat',
        'end_lng',
        'end_address',

        // Metadatos de optimización
        'optimization_params',
        'last_optimized_at',
        'created_by',

        // Auditoría
        'is_active',
    ];

    protected $casts = [
        'collection_points' => 'array',
        'optimized_sequence' => 'array',
        'days_of_week' => 'array',
        'optimization_params' => 'array',
        'total_distance_km' => 'decimal:2',
        'start_lat' => 'decimal:8',
        'start_lng' => 'decimal:8',
        'end_lat' => 'decimal:8',
        'end_lng' => 'decimal:8',
        'assigned_at' => 'datetime',
        'last_optimized_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function assignedVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_vehicle_id');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_vehicle_id');
    }

    public function scopeScheduledForToday($query)
    {
        $today = Carbon::now()->dayOfWeek; // 0 = domingo, 1 = lunes, etc.
        return $query->whereJsonContains('days_of_week', $today);
    }

    // Métodos helper
    public function assignVehicle(Vehicle $vehicle): void
    {
        $this->update([
            'assigned_vehicle_id' => $vehicle->id,
            'assigned_at' => now(),
            'status' => 'activa'
        ]);
    }

    public function unassignVehicle(): void
    {
        $this->update([
            'assigned_vehicle_id' => null,
            'assigned_at' => null,
            'status' => 'en_planificacion'
        ]);
    }

    public function getCollectionPointsCountAttribute(): int
    {
        return count($this->collection_points ?? []);
    }

    public function getIsAssignedAttribute(): bool
    {
        return !is_null($this->assigned_vehicle_id);
    }

    public function getFormattedDaysAttribute(): string
    {
        if (!$this->days_of_week) {
            return 'No programado';
        }

        $dayNames = [
            1 => 'Lun', 2 => 'Mar', 3 => 'Mié',
            4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 0 => 'Dom'
        ];

        return collect($this->days_of_week)
            ->map(fn($day) => $dayNames[$day] ?? $day)
            ->join(', ');
    }

    public function getEstimatedDurationFormattedAttribute(): string
    {
        if (!$this->estimated_duration_minutes) {
            return 'No estimado';
        }

        $hours = intval($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }

    public function isScheduledForDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->days_of_week ?? []);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'activa' => 'green',
            'inactiva' => 'gray',
            'en_planificacion' => 'yellow',
            'completada' => 'blue',
            default => 'gray'
        };
    }
}
