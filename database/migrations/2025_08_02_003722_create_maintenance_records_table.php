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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();

            // Relación con vehículo
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade')
                  ->comment('Vehículo al que pertenece el mantenimiento');

            // Información del mantenimiento
            $table->string('record_number')->unique()->comment('Número de registro de mantenimiento');
            $table->enum('type', ['preventivo', 'correctivo', 'emergencia', 'inspeccion'])
                  ->comment('Tipo de mantenimiento');
            $table->enum('status', ['programado', 'en_proceso', 'completado', 'cancelado'])
                  ->default('programado')->comment('Estado del mantenimiento');

            // Fechas y tiempo
            $table->date('scheduled_date')->comment('Fecha programada');
            $table->date('start_date')->nullable()->comment('Fecha de inicio real');
            $table->date('completion_date')->nullable()->comment('Fecha de finalización');
            $table->decimal('hours_worked', 5, 2)->nullable()->comment('Horas de trabajo');

            // Kilometraje
            $table->decimal('odometer_at_service', 10, 2)->comment('Kilometraje al momento del servicio');
            $table->decimal('next_service_km', 10, 2)->nullable()->comment('Próximo servicio en km');

            // Descripción y detalles
            $table->text('description')->comment('Descripción del trabajo realizado');
            $table->text('parts_replaced')->nullable()->comment('Partes reemplazadas');
            $table->text('observations')->nullable()->comment('Observaciones del técnico');
            $table->text('recommendations')->nullable()->comment('Recomendaciones');

            // Información del servicio
            $table->string('technician_name')->nullable()->comment('Nombre del técnico');
            $table->string('workshop')->nullable()->comment('Taller donde se realizó');
            $table->string('invoice_number')->nullable()->comment('Número de factura');

            // Costos
            $table->decimal('labor_cost', 10, 2)->default(0)->comment('Costo de mano de obra');
            $table->decimal('parts_cost', 10, 2)->default(0)->comment('Costo de repuestos');
            $table->decimal('other_costs', 10, 2)->default(0)->comment('Otros costos');
            $table->decimal('total_cost', 10, 2)->default(0)->comment('Costo total');

            // Archivos adjuntos
            $table->json('attachments')->nullable()->comment('Archivos adjuntos (facturas, fotos, etc.)');

            // Auditoría
            $table->timestamps();

            // Índices
            $table->index(['vehicle_id', 'type']);
            $table->index(['status', 'scheduled_date']);
            $table->index('completion_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
