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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_patrimonial')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->string('cpu')->nullable();
            $table->string('ram')->nullable();
            $table->string('almacenamiento')->nullable();
            $table->enum('estado', ['disponible', 'asignado', 'mantenimiento'])
                ->default('disponible');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
