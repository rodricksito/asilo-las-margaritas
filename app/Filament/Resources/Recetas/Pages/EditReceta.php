<?php

namespace App\Filament\Resources\Recetas\Pages;

use App\Filament\Resources\Recetas\RecetaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditReceta extends EditRecord
{
    protected static string $resource = RecetaResource::class;

    protected ?array $medicamentosData = null;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Al cargar el formulario, convertimos los medicamentos del pivote
     * al formato que entiende el Repeater.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['medicamentos'] = $this->record->medicamentos->map(fn ($m) => [
            'medicamento_id' => $m->id,
            'dosis' => $m->pivot->dosis,
            'frecuencia' => $m->pivot->frecuencia,
            'cantidad' => $m->pivot->cantidad,
            'duracion_dias' => $m->pivot->duracion_dias,
        ])->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->medicamentosData = $data['medicamentos'] ?? [];
        unset($data['medicamentos']);

        return $data;
    }

    /**
     * Estrategia: borrar todos los pivotes y recrear con los datos nuevos.
     * Más simple que diff y suficientemente eficiente para recetas
     * (rara vez tienen más de 5-10 medicamentos).
     */
    protected function afterSave(): void
    {
        if ($this->medicamentosData !== null) {
            $this->record->medicamentos()->detach();

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
