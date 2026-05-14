<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticuloPersonal extends Model
{
    use HasFactory;

    protected $table = 'articulos_personales';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaArticulo::class);
    }
}