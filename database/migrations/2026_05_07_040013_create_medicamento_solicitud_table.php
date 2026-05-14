<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamento_solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->cascadeOnDelete();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->integer('cantidad_solicitada');
            // lo que pide la receta para el periodo
            $table->integer('cantidad_recibida')->default(0);
            // lo que realmente trajo el familiar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamento_solicitud');
    }
};
