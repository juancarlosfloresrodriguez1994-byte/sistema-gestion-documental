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
        // 1. Limpiar campos redundantes de tramite_externo
        Schema::table('tramite_externo', function (Blueprint $table) {
            $columnasADropear = [];
            
            if (Schema::hasColumn('tramite_externo', 'usuario_actual_id')) {
                $columnasADropear[] = 'usuario_actual_id';
            }
            if (Schema::hasColumn('tramite_externo', 'area_actual_id')) {
                $columnasADropear[] = 'area_actual_id';
            }
            if (Schema::hasColumn('tramite_externo', 'equipo_actual_id')) {
                $columnasADropear[] = 'equipo_actual_id';
            }
            if (Schema::hasColumn('tramite_externo', 'sub_equipo_actual_id')) {
                $columnasADropear[] = 'sub_equipo_actual_id';
            }

            if (!empty($columnasADropear)) {
                $table->dropColumn($columnasADropear);
            }

            if (!Schema::hasColumn('tramite_externo', 'dominio_destino_id')) {
                $table->unsignedInteger('dominio_destino_id')->nullable()->after('numero_mes');
            }
        });

        // 2. Limpiar campos redundantes de movimientos_tramiteexterno
        Schema::table('movimientos_tramiteexterno', function (Blueprint $table) {
            $columnasADropearMov = [];

            if (Schema::hasColumn('movimientos_tramiteexterno', 'area_origen_id')) {
                $columnasADropearMov[] = 'area_origen_id';
            }
            if (Schema::hasColumn('movimientos_tramiteexterno', 'equipo_origen_id')) {
                $columnasADropearMov[] = 'equipo_origen_id';
            }
            if (Schema::hasColumn('movimientos_tramiteexterno', 'sub_equipo_origen_id')) {
                $columnasADropearMov[] = 'sub_equipo_origen_id';
            }
            if (Schema::hasColumn('movimientos_tramiteexterno', 'area_destino_id')) {
                $columnasADropearMov[] = 'area_destino_id';
            }
            if (Schema::hasColumn('movimientos_tramiteexterno', 'equipo_destino_id')) {
                $columnasADropearMov[] = 'equipo_destino_id';
            }
            if (Schema::hasColumn('movimientos_tramiteexterno', 'sub_equipo_destino_id')) {
                $columnasADropearMov[] = 'sub_equipo_destino_id';
            }

            if (!empty($columnasADropearMov)) {
                $table->dropColumn($columnasADropearMov);
            }

            if (!Schema::hasColumn('movimientos_tramiteexterno', 'dominio_origen_id')) {
                $table->unsignedInteger('dominio_origen_id')->nullable()->after('expediente_id');
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'dominio_destino_id')) {
                $table->unsignedInteger('dominio_destino_id')->nullable()->after('usuario_origen_id');
            }
        });

        // Permitir que usuario_destino_id sea NULL en movimientos_tramiteexterno
        DB::statement('ALTER TABLE `movimientos_tramiteexterno` MODIFY `usuario_destino_id` VARCHAR(100) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramite_externo', function (Blueprint $table) {
            if (!Schema::hasColumn('tramite_externo', 'usuario_actual_id')) {
                $table->string('usuario_actual_id', 100)->nullable();
            }
            if (!Schema::hasColumn('tramite_externo', 'area_actual_id')) {
                $table->string('area_actual_id', 100)->nullable();
            }
            if (!Schema::hasColumn('tramite_externo', 'equipo_actual_id')) {
                $table->string('equipo_actual_id', 100)->nullable();
            }
            if (!Schema::hasColumn('tramite_externo', 'sub_equipo_actual_id')) {
                $table->string('sub_equipo_actual_id', 100)->nullable();
            }
        });

        Schema::table('movimientos_tramiteexterno', function (Blueprint $table) {
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'area_origen_id')) {
                $table->string('area_origen_id', 300)->nullable();
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'equipo_origen_id')) {
                $table->string('equipo_origen_id', 300)->nullable();
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'sub_equipo_origen_id')) {
                $table->string('sub_equipo_origen_id', 100)->nullable();
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'area_destino_id')) {
                $table->string('area_destino_id', 100)->nullable();
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'equipo_destino_id')) {
                $table->string('equipo_destino_id', 100)->nullable();
            }
            if (!Schema::hasColumn('movimientos_tramiteexterno', 'sub_equipo_destino_id')) {
                $table->string('sub_equipo_destino_id', 100)->nullable();
            }
        });
    }
};
