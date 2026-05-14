<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'sucursal_id');
    }

    public function doctores(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function enfermeras(): HasMany
    {
        return $this->hasMany(Enfermera::class);
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }

    public function medicamentos(): HasMany
    {
        return $this->hasMany(Medicamento::class);
    }

    public function traspasosOrigen(): HasMany
    {
        return $this->hasMany(Traspaso::class, 'sucursal_origen_id');
    }

    public function traspasosDestino(): HasMany
    {
        return $this->hasMany(Traspaso::class, 'sucursal_destino_id');
    }
}