<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
            $table->foreignId('familiar_id')->nullable()->constrained('familiares')->nullOnDelete();
            $table->foreignId('enfermera_id')->nullable()->constrained('enfermeras')->nullOnDelete();
            $table->foreignId('receta_id')->constrained('recetas')->cascadeOnDelete();
            $table->dateTime('fecha');
            $table->string('estado')->default('incompleta');
            // valores válidos: completa, incompleta, vencida
            $table->date('fecha_limite')->nullable();
            // 3 dias después de la fecha si está incompleta (regla del PDF)
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
