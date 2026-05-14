<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'medicamentos';

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'presentacion',
        'fecha_caducidad',
        'stock',
        'activo',
    ];

    protected $casts = [
        'fecha_caducidad' => 'date',
        'activo' => 'boolean',
        'stock' => 'integer',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function recetas(): BelongsToMany
    {
        return $this->belongsToMany(Receta::class, 'medicamento_receta')
            ->withPivot('dosis', 'frecuencia', 'cantidad', 'duracion_dias')
            ->withTimestamps();
    }

    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class, 'medicamento_solicitud')
            ->withPivot('cantidad_solicitada', 'cantidad_recibida')
            ->withTimestamps();
    }

    public function traspasos(): HasMany
    {
        return $this->hasMany(Traspaso::class);
    }
}