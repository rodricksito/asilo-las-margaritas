<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'paciente_id',
        'familiar_id',
        'enfermera_id',
        'receta_id',
        'fecha',
        'estado',
        'fecha_limite',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fecha_limite' => 'date',
    ];

    // ====================================================================
    // Relaciones
    // ====================================================================

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function familiar(): BelongsTo
    {
        return $this->belongsTo(Familiar::class);
    }

    public function enfermera(): BelongsTo
    {
        return $this->belongsTo(Enfermera::class);
    }

    public function receta(): BelongsTo
    {
        return $this->belongsTo(Receta::class);
    }

    public function medicamentos(): BelongsToMany
    {
        return $this->belongsToMany(Medicamento::class, 'medicamento_solicitud')
            ->withPivot('cantidad_solicitada', 'cantidad_recibida')
            ->withTimestamps();
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaArticulo::class);
    }

    // ====================================================================
    // Scopes - clasificación de estados
    // ====================================================================

    /**
     * Solicitudes con todos los medicamentos recibidos.
     */
    public function scopeCompletas(Builder $query): Builder
    {
        return $query->where('estado', 'completa');
    }

    /**
     * Todas las solicitudes que necesitan atención (incompletas + vencidas).
     */
    public function scopePendientes(Builder $query): Builder
    {
        return $query->whereIn('estado', ['incompleta', 'vencida']);
    }

    /**
     * Solicitudes incompletas que aún están dentro del plazo de 3 días.
     */
    public function scopeIncompletasVigentes(Builder $query): Builder
    {
        return $query->where('estado', 'incompleta')
            ->where(function ($q) {
                $q->whereNull('fecha_limite')
                    ->orWhereDate('fecha_limite', '>=', now());
            });
    }

    /**
     * Solicitudes vencidas: explícitamente marcadas o incompletas con plazo expirado.
     */
    public function scopeVencidas(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('estado', 'vencida')
                ->orWhere(function ($qq) {
                    $qq->where('estado', 'incompleta')
                        ->whereDate('fecha_limite', '<', now());
                });
        });
    }

    // ====================================================================
    // Helpers
    // ====================================================================

    /**
     * Devuelve true si algún medicamento aún tiene faltantes.
     */
    public function tieneFaltantes(): bool
    {
        return $this->medicamentos
            ->contains(fn ($m) => $m->pivot->cantidad_recibida < $m->pivot->cantidad_solicitada);
    }
}
