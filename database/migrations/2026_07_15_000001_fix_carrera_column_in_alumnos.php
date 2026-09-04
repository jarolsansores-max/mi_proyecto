<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            // Verificar si existe la columna incorrecta 'carrera_id'
            if (Schema::hasColumn('alumnos', 'carrera_id')) {
                // Si existe, eliminarla
                $table->dropColumn('carrera_id');
            }
            
            // Asegurar que la columna correcta 'id_carrera' exista
            if (!Schema::hasColumn('alumnos', 'id_carrera')) {
                $table->foreignId('id_carrera')->nullable()->constrained('carreras', 'id_carrera')->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            // Revertir cambios
            if (Schema::hasColumn('alumnos', 'carrera_id')) {
                $table->dropForeign(['carrera_id']);
                $table->dropColumn('carrera_id');
            }
        });
    }
};
