<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Alert extends Model
{
    protected $fillable = [
        'code',
        'type',
        'priority',
        'status',
        'vehicle_id',
        'route_id',
        'alert_rule_id',
        'created_by',
        'assigned_to',
        'resolved_by',
        'latitude',
        'longitude',
        'location_address',
        'title',
        'description',
        'metadata',
        'detected_at',
        'acknowledged_at',
        'resolved_at',
        'closed_at',
        'response_time_minutes',
        'resolution_time_minutes',
    ];

    protected $casts = [
        'metadata' => 'array',
        'detected_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relaciones
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function alertRule(): BelongsTo
    {
        return $this->belongsTo(AlertRule::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AlertNotification::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['activa', 'reconocida']);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'activa');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'reconocida');
    }

    public function scopeResolved(Builder $query): Builder
    {
        return $query->where('status', 'resuelta');
    }

    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', 'descartada');
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeCritical(Builder $query): Builder
    {
        return $query->where('priority', 'critica');
    }

    public function scopeRecent(Builder $query, int $hours = 24): Builder
    {
        return $query->where('detected_at', '>=', now()->subHours($hours));
    }

    // Accessors
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'activa' => 'red',
            'reconocida' => 'yellow',
            'resuelta' => 'green',
            'descartada' => 'gray',
            default => 'gray'
        };
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match($this->priority) {
            'critica' => 'red',
            'alta' => 'orange',
            'media' => 'yellow',
            'baja' => 'blue',
            default => 'gray'
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'desvio_ruta' => 'Desvío de Ruta',
            'parada_prolongada' => 'Parada Prolongada',
            'perdida_senal' => 'Pérdida de Señal',
            'fuera_zona' => 'Fuera de Zona',
            'retraso_horario' => 'Retraso en Horario',
            'velocidad_excesiva' => 'Velocidad Excesiva',
            'combustible_bajo' => 'Combustible Bajo',
            'mantenimiento_vencido' => 'Mantenimiento Vencido',
            default => 'Desconocido'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'activa' => 'Activa',
            'reconocida' => 'Reconocida',
            'resuelta' => 'Resuelta',
            'descartada' => 'Descartada',
            default => 'Desconocido'
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'critica' => 'Crítica',
            'alta' => 'Alta',
            'media' => 'Media',
            'baja' => 'Baja',
            default => 'Desconocido'
        };
    }
}
