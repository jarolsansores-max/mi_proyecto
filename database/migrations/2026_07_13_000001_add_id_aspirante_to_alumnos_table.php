<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (!Schema::hasColumn('alumnos', 'id_aspirante')) {
                $table->unsignedBigInteger('id_aspirante')->nullable()->after('id_carrera');
                $table->foreign('id_aspirante')
                    ->references('id_aspirantes')
                    ->on('aspirantes')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (Schema::hasColumn('alumnos', 'id_aspirante')) {
                $table->dropForeign(['id_aspirante']);
                $table->dropColumn('id_aspirante');
            }
        });
    }
};
