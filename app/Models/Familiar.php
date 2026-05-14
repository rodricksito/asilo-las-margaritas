<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Familiar extends Model
{
    use HasFactory;

    protected $table = 'familiares';

    protected $fillable = [
        'nombre',
        'parentesco',
        'telefono',
        'email',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'familiar_paciente')
            ->withPivot('es_principal')
            ->withTimestamps();
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}