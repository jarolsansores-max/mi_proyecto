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
            // Modificar columnas incorrectas para tener valor por defecto
            if (Schema::hasColumn('alumnos', 'apellido_paterno')) {
                DB::statement("ALTER TABLE alumnos MODIFY COLUMN apellido_paterno VARCHAR(255) DEFAULT ''");
            }
            if (Schema::hasColumn('alumnos', 'apellido_materno')) {
                DB::statement("ALTER TABLE alumnos MODIFY COLUMN apellido_materno VARCHAR(255) DEFAULT ''");
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
            if (Schema::hasColumn('alumnos', 'apellido_paterno')) {
                DB::statement("ALTER TABLE alumnos MODIFY COLUMN apellido_paterno VARCHAR(255) NULL");
            }
            if (Schema::hasColumn('alumnos', 'apellido_materno')) {
                DB::statement("ALTER TABLE alumnos MODIFY COLUMN apellido_materno VARCHAR(255) NULL");
            }
        });
    }
};
