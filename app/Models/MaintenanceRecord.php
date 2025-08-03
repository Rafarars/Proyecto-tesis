<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    protected $fillable = [
        // Relación
        'vehicle_id',

        // Información del mantenimiento
        'record_number',
        'type',
        'status',

        // Fechas y tiempo
        'scheduled_date',
        'start_date',
        'completion_date',
        'hours_worked',

        // Kilometraje
        'odometer_at_service',
        'next_service_km',

        // Descripción y detalles
        'description',
        'parts_replaced',
        'observations',
        'recommendations',

        // Información del servicio
        'technician_name',
        'workshop',
        'invoice_number',

        // Costos
        'labor_cost',
        'parts_cost',
        'other_costs',
        'total_cost',

        // Archivos adjuntos
        'attachments',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'hours_worked' => 'decimal:2',
        'odometer_at_service' => 'decimal:2',
        'next_service_km' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'other_costs' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'attachments' => 'array',
    ];

    // Relaciones
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Scopes
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completado');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['programado', 'en_proceso']);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('scheduled_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_date', '<', now())
                    ->where('status', 'programado');
    }

    // Métodos helper
    public function getDurationAttribute(): ?int
    {
        if ($this->start_date && $this->completion_date) {
            return $this->start_date->diffInDays($this->completion_date);
        }
        return null;
    }

    public function isOverdue(): bool
    {
        return $this->scheduled_date < now() && $this->status === 'programado';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'programado' => 'blue',
            'en_proceso' => 'yellow',
            'completado' => 'green',
            'cancelado' => 'red',
            default => 'gray'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'preventivo' => 'green',
            'correctivo' => 'orange',
            'emergencia' => 'red',
            'inspeccion' => 'blue',
            default => 'gray'
        };
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (!$record->record_number) {
                $record->record_number = 'MNT-' . now()->format('Y') . '-' . str_pad(
                    static::whereYear('created_at', now()->year)->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });

        static::updated(function ($record) {
            if ($record->status === 'completado' && $record->completion_date) {
                // Actualizar el vehículo con la fecha del último mantenimiento
                $record->vehicle->update([
                    'last_maintenance' => $record->completion_date,
                    'odometer_km' => $record->odometer_at_service,
                ]);
            }
        });
    }
}
