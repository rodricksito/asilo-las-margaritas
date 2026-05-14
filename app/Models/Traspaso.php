<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traspaso extends Model
{
    use HasFactory;

    protected $table = 'traspasos';

    protected $fillable = [
        'sucursal_origen_id',
        'sucursal_destino_id',
        'medicamento_id',
        'usuario_id',
        'cantidad',
        'fecha',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'cantidad' => 'integer',
    ];

    public function sucursalOrigen(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }

    public function sucursalDestino(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}