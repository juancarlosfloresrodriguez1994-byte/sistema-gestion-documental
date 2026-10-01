<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_pendiente', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8);
            $table->string('nombres', 255);
            $table->string('apellidos', 255);
            $table->string('nombre_completo', 255);
            $table->string('correo', 255);
            $table->string('nickname', 100);
            $table->string('password', 255);
            $table->string('token', 64)->unique();
            $table->timestamp('expira_en');
            $table->boolean('verificado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_pendiente');
    }
};
