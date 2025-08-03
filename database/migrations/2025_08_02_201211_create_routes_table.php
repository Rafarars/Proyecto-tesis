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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();

            // Información básica de la ruta
            $table->string('name'); // Nombre de la ruta (ej: "Ruta Centro Norte")
            $table->string('code')->unique(); // Código único (ej: "RT-001")
            $table->text('description')->nullable(); // Descripción de la ruta

            // Estado y configuración
            $table->enum('status', ['activa', 'inactiva', 'en_planificacion', 'completada'])
                  ->default('en_planificacion');
            $table->enum('type', ['recoleccion_residencial', 'recoleccion_comercial', 'recoleccion_mixta'])
                  ->default('recoleccion_mixta');

            // Datos de optimización
            $table->json('collection_points'); // Puntos de recolección [{"lat": x, "lng": y, "address": "", "priority": 1}]
            $table->json('optimized_sequence')->nullable(); // Secuencia optimizada de puntos
            $table->decimal('total_distance_km', 8, 2)->nullable(); // Distancia total estimada
            $table->integer('estimated_duration_minutes')->nullable(); // Duración estimada en minutos

            // Asignación de vehículo
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable(); // Cuándo se asignó el vehículo

            // Horarios y frecuencia
            $table->time('start_time')->nullable(); // Hora de inicio programada
            $table->time('end_time')->nullable(); // Hora de fin estimada
            $table->json('days_of_week')->nullable(); // Días de la semana [1,2,3,4,5] (lunes a viernes)

            // Ubicación de inicio y fin
            $table->decimal('start_lat', 10, 8)->nullable(); // Punto de inicio (depósito)
            $table->decimal('start_lng', 11, 8)->nullable();
            $table->string('start_address')->nullable();
            $table->decimal('end_lat', 10, 8)->nullable(); // Punto de fin (puede ser diferente al inicio)
            $table->decimal('end_lng', 11, 8)->nullable();
            $table->string('end_address')->nullable();

            // Metadatos de optimización
            $table->json('optimization_params')->nullable(); // Parámetros usados para optimización
            $table->timestamp('last_optimized_at')->nullable(); // Última vez que se optimizó
            $table->foreignId('created_by')->constrained('users'); // Usuario que creó la ruta

            // Auditoría
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Índices
            $table->index(['status', 'is_active']);
            $table->index(['assigned_vehicle_id', 'status']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
