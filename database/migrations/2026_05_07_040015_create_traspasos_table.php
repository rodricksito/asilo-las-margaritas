<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_origen_id')->constrained('sucursales')->restrictOnDelete();
            $table->foreignId('sucursal_destino_id')->constrained('sucursales')->restrictOnDelete();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('cantidad');
            $table->date('fecha');
            $table->string('estado')->default('pendiente');
            // valores válidos: pendiente, completado, cancelado
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traspasos');
    }
};
