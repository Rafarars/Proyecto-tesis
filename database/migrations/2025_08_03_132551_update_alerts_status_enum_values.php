<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero modificamos la columna enum para incluir todos los valores
        DB::statement("ALTER TABLE alerts MODIFY COLUMN status ENUM('abierta', 'en_atencion', 'resuelta', 'cerrada', 'activa', 'reconocida', 'descartada') DEFAULT 'abierta'");

        // Luego actualizamos los valores existentes
        DB::table('alerts')->where('status', 'abierta')->update(['status' => 'activa']);
        DB::table('alerts')->where('status', 'en_atencion')->update(['status' => 'reconocida']);
        DB::table('alerts')->where('status', 'cerrada')->update(['status' => 'descartada']);
        // 'resuelta' se mantiene igual

        // Finalmente, removemos los valores antiguos del enum
        DB::statement("ALTER TABLE alerts MODIFY COLUMN status ENUM('activa', 'reconocida', 'resuelta', 'descartada') DEFAULT 'activa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertimos los valores
        DB::table('alerts')->where('status', 'activa')->update(['status' => 'abierta']);
        DB::table('alerts')->where('status', 'reconocida')->update(['status' => 'en_atencion']);
        DB::table('alerts')->where('status', 'descartada')->update(['status' => 'cerrada']);

        // Revertimos la columna enum
        DB::statement("ALTER TABLE alerts MODIFY COLUMN status ENUM('abierta', 'en_atencion', 'resuelta', 'cerrada') DEFAULT 'abierta'");
    }
};
