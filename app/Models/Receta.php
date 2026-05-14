<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receta extends Model
{
    use HasFactory;

    protected $table = 'recetas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha',
        'vigencia',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'vigencia' => 'date',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicamentos(): BelongsToMany
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_receta')
            ->withPivot('dosis', 'frecuencia', 'cantidad', 'duracion_dias')
            ->withTimestamps();
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}