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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre descriptivo de la regla
            $table->string('code')->unique(); // Código único (AR-001, AR-002, etc.)
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
            $table->text('description')->nullable();

            // Configuración de la regla
            $table->json('conditions'); // Condiciones específicas (distancia, tiempo, etc.)
            $table->enum('priority', ['critica', 'alta', 'media', 'baja'])->default('media');
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_resolve')->default(false); // Si se resuelve automáticamente

            // Configuración de notificaciones
            $table->boolean('send_notification')->default(true);
            $table->json('notification_users')->nullable(); // IDs de usuarios a notificar
            $table->integer('escalation_time_minutes')->nullable(); // Tiempo para escalar si no se atiende

            // Configuración de horarios
            $table->time('active_from')->nullable(); // Hora de inicio de monitoreo
            $table->time('active_to')->nullable(); // Hora de fin de monitoreo
            $table->json('active_days')->nullable(); // Días de la semana activos [0,1,2,3,4,5,6]

            // Filtros
            $table->json('vehicle_types')->nullable(); // Tipos de vehículos aplicables
            $table->json('route_types')->nullable(); // Tipos de rutas aplicables

            // Auditoría
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // Índices
            $table->index(['type', 'is_active']);
            $table->index(['is_active', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_rules');
    }
};
