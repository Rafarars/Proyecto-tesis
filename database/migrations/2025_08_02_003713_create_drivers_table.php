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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            // Información personal
            $table->string('employee_code')->unique()->comment('Código de empleado');
            $table->string('first_name')->comment('Nombres');
            $table->string('last_name')->comment('Apellidos');
            $table->string('document_type')->default('cedula')->comment('Tipo de documento');
            $table->string('document_number')->unique()->comment('Número de documento');
            $table->date('birth_date')->comment('Fecha de nacimiento');
            $table->enum('gender', ['masculino', 'femenino', 'otro'])->comment('Género');

            // Información de contacto
            $table->string('phone')->nullable()->comment('Teléfono principal');
            $table->string('phone_emergency')->nullable()->comment('Teléfono de emergencia');
            $table->string('email')->nullable()->comment('Correo electrónico');
            $table->text('address')->nullable()->comment('Dirección de residencia');

            // Información laboral
            $table->date('hire_date')->comment('Fecha de contratación');
            $table->enum('status', ['activo', 'inactivo', 'vacaciones', 'licencia', 'suspendido'])
                  ->default('activo')->comment('Estado laboral');
            $table->enum('shift', ['mañana', 'tarde', 'noche', 'rotativo'])
                  ->default('mañana')->comment('Turno de trabajo');
            $table->decimal('salary', 10, 2)->nullable()->comment('Salario base');

            // Licencias y certificaciones
            $table->string('license_number')->comment('Número de licencia de conducir');
            $table->enum('license_type', ['A', 'B', 'C', 'D', 'E'])->comment('Tipo de licencia');
            $table->date('license_expiry')->comment('Vencimiento de licencia');
            $table->boolean('has_hazmat_cert')->default(false)->comment('Certificación materiales peligrosos');
            $table->date('hazmat_expiry')->nullable()->comment('Vencimiento certificación materiales peligrosos');

            // Información médica y seguridad
            $table->date('medical_exam_date')->nullable()->comment('Fecha último examen médico');
            $table->date('medical_exam_expiry')->nullable()->comment('Vencimiento examen médico');
            $table->text('medical_restrictions')->nullable()->comment('Restricciones médicas');
            $table->text('emergency_contact')->nullable()->comment('Contacto de emergencia');

            // Auditoría
            $table->text('notes')->nullable()->comment('Notas adicionales');
            $table->boolean('is_active')->default(true)->comment('Si el conductor está activo en el sistema');
            $table->timestamps();

            // Índices
            $table->index(['status', 'is_active']);
            $table->index('license_expiry');
            $table->index('medical_exam_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
