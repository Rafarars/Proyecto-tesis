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
        Schema::create('vehicles_table_complete', function (Blueprint $table) {
            $table->id();

            // Información básica del vehículo
            $table->string('license_plate')->unique()->comment('Placa del vehículo');
            $table->string('code')->unique()->comment('Código interno (ej: CAR-001)');
            $table->string('brand')->comment('Marca del vehículo');
            $table->string('model')->comment('Modelo del vehículo');
            $table->year('year')->comment('Año de fabricación');
            $table->string('color')->comment('Color del vehículo');
            $table->string('vin')->nullable()->comment('Número de chasis/VIN');

            // Especificaciones técnicas
            $table->enum('type', ['compactador', 'camion', 'pickup', 'van', 'volqueta'])
                  ->comment('Tipo de vehículo');
            $table->decimal('capacity_tons', 5, 2)->comment('Capacidad en toneladas');
            $table->decimal('fuel_capacity_liters', 6, 2)->comment('Capacidad del tanque en litros');
            $table->enum('fuel_type', ['gasolina', 'diesel', 'gas', 'electrico'])
                  ->default('diesel')->comment('Tipo de combustible');
            $table->string('engine')->nullable()->comment('Especificaciones del motor');

            // Estado y operación
            $table->enum('status', ['activo', 'mantenimiento', 'fuera_servicio', 'reparacion'])
                  ->default('activo')->comment('Estado actual del vehículo');
            $table->unsignedBigInteger('driver_id')->nullable()
                  ->comment('Conductor asignado actualmente');
            $table->decimal('odometer_km', 10, 2)->default(0)->comment('Kilometraje actual');
            $table->date('last_maintenance')->nullable()->comment('Fecha del último mantenimiento');
            $table->date('next_maintenance')->nullable()->comment('Fecha del próximo mantenimiento');

            // Ubicación y seguimiento
            $table->decimal('current_lat', 10, 8)->nullable()->comment('Latitud actual');
            $table->decimal('current_lng', 11, 8)->nullable()->comment('Longitud actual');
            $table->decimal('current_speed', 5, 2)->default(0)->comment('Velocidad actual en km/h');
            $table->timestamp('location_updated_at')->nullable()->comment('Última actualización de ubicación');

            // Información administrativa
            $table->date('purchase_date')->nullable()->comment('Fecha de compra');
            $table->decimal('purchase_price', 12, 2)->nullable()->comment('Precio de compra');
            $table->string('insurance_policy')->nullable()->comment('Número de póliza de seguro');
            $table->date('insurance_expiry')->nullable()->comment('Vencimiento del seguro');
            $table->text('notes')->nullable()->comment('Notas adicionales');

            // Auditoría
            $table->boolean('is_active')->default(true)->comment('Si el vehículo está activo en el sistema');
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['status', 'is_active']);
            $table->index(['type', 'status']);
            $table->index('driver_id');
            $table->index(['current_lat', 'current_lng']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_table_complete');
    }
};
