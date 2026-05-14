<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')->constrained('sucursales')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('presentacion');
            // ej: "Tabletas 500mg", "Jarabe 120ml", "Cápsulas 250mg"
            $table->date('fecha_caducidad');
            // regla del PDF: al registrar, fecha_caducidad debe ser >= hoy + 3 meses
            // (la validacion la pondremos en el Filament Resource)
            $table->integer('stock')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
