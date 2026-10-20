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
        // 1. Ampliar campos en tabla `area`
        Schema::table('area', function (Blueprint $table) {
            if (!Schema::hasColumn('area', 'siglas')) {
                $table->string('siglas', 50)->nullable()->after('nombre_area');
            }
            if (!Schema::hasColumn('area', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('siglas');
            }
            if (!Schema::hasColumn('area', 'responsable')) {
                $table->string('responsable', 255)->nullable()->after('descripcion');
            }
        });

        // 2. Ampliar campos en tabla `equipo`
        Schema::table('equipo', function (Blueprint $table) {
            if (!Schema::hasColumn('equipo', 'siglas')) {
                $table->string('siglas', 50)->nullable()->after('nombre_equipo');
            }
            if (!Schema::hasColumn('equipo', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('siglas');
            }
            if (!Schema::hasColumn('equipo', 'responsable')) {
                $table->string('responsable', 255)->nullable()->after('descripcion');
            }
        });

        // 3. Ampliar campos en tabla `subequipo`
        Schema::table('subequipo', function (Blueprint $table) {
            if (!Schema::hasColumn('subequipo', 'siglas')) {
                $table->string('siglas', 50)->nullable()->after('nombre_subequipo');
            }
            if (!Schema::hasColumn('subequipo', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('siglas');
            }
            if (!Schema::hasColumn('subequipo', 'responsable')) {
                $table->string('responsable', 255)->nullable()->after('descripcion');
            }
        });

        // 4. Agregar enlace a subequipo en tabla `dominio`
        Schema::table('dominio', function (Blueprint $table) {
            if (!Schema::hasColumn('dominio', 'domi_idsubequipo')) {
                $table->bigInteger('domi_idsubequipo')->nullable()->after('domi_idequipo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dominio', function (Blueprint $table) {
            if (Schema::hasColumn('dominio', 'domi_idsubequipo')) {
                $table->dropColumn('domi_idsubequipo');
            }
        });

        Schema::table('subequipo', function (Blueprint $table) {
            $table->dropColumn(['siglas', 'descripcion', 'responsable']);
        });

        Schema::table('equipo', function (Blueprint $table) {
            $table->dropColumn(['siglas', 'descripcion', 'responsable']);
        });

        Schema::table('area', function (Blueprint $table) {
            $table->dropColumn(['siglas', 'descripcion', 'responsable']);
        });
    }
};
