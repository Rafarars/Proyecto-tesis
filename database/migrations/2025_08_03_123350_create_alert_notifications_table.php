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
        Schema::create('alert_notifications', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('alert_id')->constrained('alerts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Información de la notificación
            $table->enum('type', ['alert_created', 'alert_escalated', 'alert_assigned', 'alert_resolved']);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales de la notificación

            // Estado de la notificación
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->boolean('is_read')->default(false);

            // Configuración
            $table->enum('channel', ['in_app', 'email', 'push'])->default('in_app');
            $table->enum('priority', ['critica', 'alta', 'media', 'baja'])->default('media');

            $table->timestamps();

            // Índices
            $table->index(['user_id', 'is_read']);
            $table->index(['alert_id', 'type']);
            $table->index(['created_at', 'is_read']);
            $table->unique(['alert_id', 'user_id', 'type']); // Evitar notificaciones duplicadas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_notifications');
    }
};
