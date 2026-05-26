<?php

namespace App\Filament\Resources\Medicamentos\Pages;

use App\Filament\Resources\Medicamentos\MedicamentoResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicamento extends CreateRecord
{
    protected static string $resource = MedicamentoResource::class;

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
}
