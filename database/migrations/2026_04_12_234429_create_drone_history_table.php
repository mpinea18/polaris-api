<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drone_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drone_id')->constrained('drones')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['garantia', 'mantenimiento', 'reparacion']);
            $table->text('descripcion');
            $table->text('partes_reemplazadas')->nullable();
            $table->string('seriales_nuevos')->nullable();
            $table->enum('resultado', [
                'exitoso',
                'garantia_aprobada',
                'garantia_negada',
                'reemplazo_unidad',
                'reparacion_parcial'
            ]);
            $table->integer('horas_trabajo')->nullable();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->foreignId('warranty_id')->nullable()->constrained('warranties')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drone_history');
    }
};