<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('drone_id')->nullable()->constrained('drones')->onDelete('set null');
            $table->enum('tipo', ['mantenimiento', 'reparacion', 'inspeccion'])->default('mantenimiento');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['tecnico_id']);
            $table->dropForeign(['drone_id']);
            $table->dropColumn(['empresa_id', 'tecnico_id', 'drone_id', 'tipo']);
        });
    }
};