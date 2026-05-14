<?php

namespace App\Filament\Resources\Medicamentos\Pages;

use App\Filament\Resources\Medicamentos\MedicamentoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMedicamento extends EditRecord
{
    protected static string $resource = MedicamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
