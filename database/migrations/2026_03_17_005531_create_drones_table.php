<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('drones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plate')->unique();
            $table->string('model');
            $table->integer('hours')->default(0);
            $table->enum('status', ['operational','maintenance','critical'])->default('operational');
            $table->date('last_service')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('drones');
    }
};