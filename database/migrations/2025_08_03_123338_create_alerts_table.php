<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código único de la alerta (AL-001, AL-002, etc.)
            $table->enum('type', [
                'desvio_ruta',
                'parada_prolongada',
                'perdida_senal',
                'fuera_zona',
                'retraso_horario',
                'velocidad_excesiva',
                'combustible_bajo',
                'mantenimiento_vencido'
            ]);
            $table->enum('priority', ['critica', 'alta', 'media', 'baja'])->default('media');
            $table->enum('status', ['abierta', 'en_atencion', 'resuelta', 'cerrada'])->default('abierta');

            // Relaciones
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('route_id')->nullable()->constrained('routes')->onDelete('set null');
            $table->unsignedBigInteger('alert_rule_id')->nullable(); // Será referenciada después
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');

            // Datos de ubicación
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_address')->nullable();

            // Información de la alerta
            $table->string('title');
            $table->text('description');
            $table->json('metadata')->nullable(); // Datos adicionales específicos del tipo de alerta

            // Timestamps específicos
            $table->timestamp('detected_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            // Métricas
            $table->integer('response_time_minutes')->nullable(); // Tiempo de respuesta en minutos
            $table->integer('resolution_time_minutes')->nullable(); // Tiempo de resolución en minutos

            $table->timestamps();

            // Índices
            $table->index(['type', 'status']);
            $table->index(['vehicle_id', 'detected_at']);
            $table->index(['priority', 'status']);
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
