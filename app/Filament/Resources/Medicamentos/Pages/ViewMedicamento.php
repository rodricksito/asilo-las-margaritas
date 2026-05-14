<?php

namespace App\Filament\Resources\Medicamentos\Pages;

use App\Filament\Resources\Medicamentos\MedicamentoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMedicamento extends ViewRecord
{
    protected static string $resource = MedicamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
