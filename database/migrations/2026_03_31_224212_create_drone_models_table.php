<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drone_models', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->string('tipo_uas'); // Multirotor, VTOL, Ala Fija, etc.
            $table->integer('num_motores')->nullable();
            $table->decimal('peso_fabrica_gr', 8, 2)->nullable();
            $table->string('pais_fabricacion')->default('China');
            $table->string('autonomia_min')->nullable();
            $table->string('camara')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drone_models');
    }
};
