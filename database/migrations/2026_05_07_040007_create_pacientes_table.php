<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctores')->nullOnDelete();
            $table->string('nombre');
            $table->date('fecha_nacimiento');
            $table->date('fecha_ingreso');
            $table->string('estado')->default('activo');
            // valores válidos: activo, dado_de_alta, fallecido
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
