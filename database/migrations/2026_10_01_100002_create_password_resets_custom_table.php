<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_resets_custom', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 255);
            $table->string('token', 64)->unique();
            $table->timestamp('expira_en');
            $table->boolean('usado')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets_custom');
    }
};
