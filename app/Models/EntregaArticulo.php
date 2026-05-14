<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntregaArticulo extends Model
{
    use HasFactory;

    protected $table = 'entregas_articulos';

    protected $fillable = [
        'solicitud_id',
        'articulo_id',
        'paciente_id',
        'cantidad',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'integer',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function articulo(): BelongsTo
    {
        return $this->belongsTo(ArticuloPersonal::class, 'articulo_id');
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}