<?php

namespace App\Filament\Resources\Recetas\Pages;

use App\Filament\Resources\Recetas\RecetaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReceta extends CreateRecord
{
    protected static string $resource = RecetaResource::class;

    /**
     * Almacenamos los datos del Repeater aquí porque hay que removerlos
     * antes de crear la Receta (no son columnas del modelo, son del pivote).
     */
    protected ?array $medicamentosData = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Sacamos los medicamentos del array — Receta::create() no debe verlos
        $this->medicamentosData = $data['medicamentos'] ?? [];
        unset($data['medicamentos']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Ya tenemos $this->record (la receta recién creada).
        // Adjuntamos cada medicamento al pivote con sus datos extra.
        if ($this->medicamentosData) {
            foreach ($this->medicamentosData as $item) {
                $this->record->medicamentos()->attach($item['medicamento_id'], [
                    'dosis' => $item['dosis'],
                    'frecuencia' => $item['frecuencia'],
                    'cantidad' => $item['cantidad'],
                    'duracion_dias' => $item['duracion_dias'] ?? null,
                ]);
            }
        }
    }
}
