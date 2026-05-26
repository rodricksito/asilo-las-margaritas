<?php

namespace App\Filament\Resources\Recetas\Pages;

use App\Filament\Resources\Recetas\RecetaResource;
use Filament\Actions\Action;
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
            Action::make('volver')
                ->label('Volver al listado')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

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
