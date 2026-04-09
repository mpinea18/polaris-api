<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('drone_id')->nullable()->constrained('drones');
            $table->foreignId('tech_id')->nullable()->constrained('users');

            // Datos del solicitante
            $table->string('nombre');
            $table->string('cedula_nit');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('telefono');

            // Datos del equipo
            $table->string('codigo_producto');
            $table->string('nombre_producto');
            $table->string('numero_factura');
            $table->date('fecha_compra');
            $table->string('numero_serial');
            $table->string('serial_baterias')->nullable();
            $table->string('serial_control')->nullable();
            $table->string('serial_cargador')->nullable();
            $table->text('falla_reportada');
            $table->text('contenido');
            $table->boolean('usuario_final')->default(true);
            $table->boolean('sufrió_accidente')->default(false);
            $table->text('observaciones')->nullable();

            // Estado del proceso
            $table->enum('status', ['pendiente','aprobada','negada','en_proceso','completada'])->default('pendiente');
            $table->text('motivo_negacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};