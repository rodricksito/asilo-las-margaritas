<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'sucursal_id',
        'doctor_id',
        'nombre',
        'fecha_nacimiento',
        'fecha_ingreso',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function familiares(): BelongsToMany
    {
        return $this->belongsToMany(Familiar::class, 'familiar_paciente')
            ->withPivot('es_principal')
            ->withTimestamps();
    }

    public function recetas(): HasMany
    {
        return $this->hasMany(Receta::class);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaArticulo::class);
    }
}