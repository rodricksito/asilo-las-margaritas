<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('familiar_paciente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('familiar_id')->constrained('familiares')->cascadeOnDelete();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->boolean('es_principal')->default(false);
            // marca el contacto principal de cada paciente
            $table->timestamps();

            $table->unique(['familiar_id', 'paciente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('familiar_paciente');
    }
};
