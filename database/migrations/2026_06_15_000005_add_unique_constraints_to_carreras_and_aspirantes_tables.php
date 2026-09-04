<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carreras', function (Blueprint $table) {
            $table->unique('nombre');
        });

        Schema::table('aspirantes', function (Blueprint $table) {
            $table->unique('curp');
        });
    }

    public function down(): void
    {
        Schema::table('aspirantes', function (Blueprint $table) {
            $table->dropUnique(['curp']);
        });

        Schema::table('carreras', function (Blueprint $table) {
            $table->dropUnique(['nombre']);
        });
    }
};