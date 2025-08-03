<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class AlertRule extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'conditions',
        'threshold_value',
        'threshold_unit',
        'priority',
        'is_active',
        'auto_resolve',
        'send_notification',
        'notification_users',
        'escalation_time_minutes',
        'active_from',
        'active_to',
        'active_days',
        'vehicle_types',
        'route_types',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'conditions' => 'array',
        'notification_users' => 'array',
        'active_days' => 'array',
        'vehicle_types' => 'array',
        'route_types' => 'array',
        'is_active' => 'boolean',
        'auto_resolve' => 'boolean',
        'send_notification' => 'boolean',
        'active_from' => 'datetime:H:i',
        'active_to' => 'datetime:H:i',
    ];

    // Relaciones
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeWithNotifications(Builder $query): Builder
    {
        return $query->where('send_notification', true);
    }

    // Accessors
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

    public function getStatusBadgeColorAttribute(): string
    {
        return $this->is_active ? 'green' : 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Activa' : 'Inactiva';
    }

    public function getFormattedActiveDaysAttribute(): string
    {
        if (!$this->active_days || empty($this->active_days)) {
            return 'Todos los días';
        }

        $days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $activeDays = collect($this->active_days)->map(fn($day) => $days[$day] ?? '')->filter();

        return $activeDays->join(', ');
    }

    public function getFormattedActiveHoursAttribute(): string
    {
        if (!$this->active_from || !$this->active_to) {
            return '24 horas';
        }

        return $this->active_from->format('H:i') . ' - ' . $this->active_to->format('H:i');
    }

    // Métodos de utilidad
    public function isActiveNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Verificar día de la semana
        if ($this->active_days && !empty($this->active_days)) {
            if (!in_array($now->dayOfWeek, $this->active_days)) {
                return false;
            }
        }

        // Verificar horario
        if ($this->active_from && $this->active_to) {
            $currentTime = $now->format('H:i');
            $activeFrom = $this->active_from->format('H:i');
            $activeTo = $this->active_to->format('H:i');

            if ($currentTime < $activeFrom || $currentTime > $activeTo) {
                return false;
            }
        }

        return true;
    }

    public function appliesToVehicle(Vehicle $vehicle): bool
    {
        if (!$this->vehicle_types || empty($this->vehicle_types)) {
            return true;
        }

        return in_array($vehicle->type, $this->vehicle_types);
    }

    public function appliesToRoute($route): bool
    {
        if (!$route || !$this->route_types || empty($this->route_types)) {
            return true;
        }

        return in_array($route->type, $this->route_types);
    }
}
