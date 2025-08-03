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
        Schema::table('alert_rules', function (Blueprint $table) {
            // Agregar columnas para valores umbral simplificados
            $table->decimal('threshold_value', 10, 2)->nullable()->after('description');
            $table->string('threshold_unit', 50)->nullable()->after('threshold_value');

            // Hacer opcional la columna conditions para compatibilidad
            $table->json('conditions')->nullable()->change();

            // Hacer opcionales las columnas de auditoría para simplificar
            $table->foreignId('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_rules', function (Blueprint $table) {
            $table->dropColumn(['threshold_value', 'threshold_unit']);
            $table->json('conditions')->nullable(false)->change();
            $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
