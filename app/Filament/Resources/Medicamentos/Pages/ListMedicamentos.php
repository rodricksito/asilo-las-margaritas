<?php

namespace App\Filament\Resources\Medicamentos\Pages;

use App\Filament\Resources\Medicamentos\MedicamentoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedicamentos extends ListRecords
{
    protected static string $resource = MedicamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
