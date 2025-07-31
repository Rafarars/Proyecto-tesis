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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->year('year');
            $table->enum('type', ['truck', 'compactor', 'pickup', 'van']);
            $table->decimal('capacity', 8, 2); // Capacidad en toneladas
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');

            // Ubicación actual del vehículo (Point) - NOT NULL para índice espacial
            $table->geometry('current_location');

            // Ruta asignada (LineString) - nullable, sin índice por ahora
            $table->geometry('assigned_route')->nullable();

            // Área de cobertura (Polygon) - nullable, sin índice por ahora
            $table->geometry('coverage_area')->nullable();

            $table->timestamps();

            // Índice espacial solo para current_location (NOT NULL)
            $table->spatialIndex('current_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
