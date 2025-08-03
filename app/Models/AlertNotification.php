<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AlertNotification extends Model
{
    protected $fillable = [
        'alert_id',
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'sent_at',
        'read_at',
        'acknowledged_at',
        'is_read',
        'channel',
        'priority',
    ];

    protected $casts = [
        'data' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    // Relaciones
    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority(Builder $query, string $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeRecent(Builder $query, int $hours = 24): Builder
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'alert_created' => 'Alerta Creada',
            'alert_escalated' => 'Alerta Escalada',
            'alert_assigned' => 'Alerta Asignada',
            'alert_resolved' => 'Alerta Resuelta',
            default => 'Notificación'
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

    public function getChannelLabelAttribute(): string
    {
        return match($this->channel) {
            'in_app' => 'En Aplicación',
            'email' => 'Correo Electrónico',
            'push' => 'Notificación Push',
            default => 'Desconocido'
        };
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Métodos de utilidad
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsAcknowledged(): void
    {
        $this->update([
            'acknowledged_at' => now(),
        ]);
    }

    public function markAsSent(): void
    {
        $this->update([
            'sent_at' => now(),
        ]);
    }
}
