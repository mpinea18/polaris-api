<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('drones', function (Blueprint $table) {
            $table->string('marca')->nullable();
            $table->foreignId('drone_model_id')->nullable()->constrained('drone_models');
            $table->string('numero_serie')->nullable();
            $table->string('tipo_uas')->nullable();
            $table->string('num_motores')->nullable();
            $table->string('color')->nullable();
            $table->decimal('peso_real', 8, 2)->nullable();
            $table->string('tipo_registro')->default('Primera vez');
            $table->json('sistemas')->nullable();
            $table->string('poliza')->nullable();
            $table->string('sistema_recuperacion')->nullable();
            $table->string('equipo_fabrica')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('drones', function (Blueprint $table) {
            $table->dropColumn([
                'marca','drone_model_id','numero_serie','tipo_uas',
                'num_motores','color','peso_real','tipo_registro',
                'sistemas','poliza','sistema_recuperacion','equipo_fabrica'
            ]);
        });
    }
};