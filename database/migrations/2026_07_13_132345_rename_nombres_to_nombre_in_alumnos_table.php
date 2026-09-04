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
        Schema::table('alumnos', function (Blueprint $table) {
            // Eliminar la columna 'nombres' incorrecta si existe
            if (Schema::hasColumn('alumnos', 'nombres')) {
                $table->dropColumn('nombres');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            // Revertir: recrear la columna 'nombres' si no existe
            if (!Schema::hasColumn('alumnos', 'nombres')) {
                $table->string('nombres')->nullable();
            }
        });
    }
};
