<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamento_receta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->cascadeOnDelete();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->string('dosis');
            // ej: "1 tableta", "10 ml", "media cucharada"
            $table->string('frecuencia');
            // ej: "cada 8 horas", "antes de dormir", "con cada comida"
            $table->integer('cantidad');
            // total de unidades necesarias para el periodo de la receta
            $table->integer('duracion_dias')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamento_receta');
    }
};
