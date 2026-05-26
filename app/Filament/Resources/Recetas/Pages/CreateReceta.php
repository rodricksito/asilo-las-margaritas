<?php

namespace App\Filament\Resources\Recetas\Pages;

use App\Filament\Resources\Recetas\RecetaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateReceta extends CreateRecord
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
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->medicamentosData = $data['medicamentos'] ?? [];
        unset($data['medicamentos']);

        return $data;
    }

    protected function afterCreate(): void
    {
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
