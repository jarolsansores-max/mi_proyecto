<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alumnos')) {
            Schema::create('alumnos', function (Blueprint $table) {
                $table->id('id_alumnos');
                $table->string('matricula')->unique();
                $table->string('curp')->unique();
                $table->string('nombre');
                $table->string('ap_paterno');
                $table->string('ap_materno')->nullable();
                $table->date('fecha_nacimiento')->nullable();
                $table->string('email')->nullable();
                $table->string('telefono')->nullable();
                $table->foreignId('id_carrera')->constrained('carreras', 'id_carrera')->cascadeOnDelete();
                $table->string('estatus')->default('Activo');
                $table->timestamps();
            });

            return;
        }

        Schema::table('alumnos', function (Blueprint $table) {
            if (!Schema::hasColumn('alumnos', 'matricula')) {
                $table->string('matricula')->unique()->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'curp')) {
                $table->string('curp')->unique()->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'nombre')) {
                $table->string('nombre')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'ap_paterno')) {
                $table->string('ap_paterno')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'ap_materno')) {
                $table->string('ap_materno')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'fecha_nacimiento')) {
                $table->date('fecha_nacimiento')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'telefono')) {
                $table->string('telefono')->nullable();
            }
            if (!Schema::hasColumn('alumnos', 'id_carrera')) {
                $table->foreignId('id_carrera')->nullable()->constrained('carreras', 'id_carrera')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('alumnos', 'estatus')) {
                $table->string('estatus')->default('Activo');
            }
            if (!Schema::hasColumn('alumnos', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};