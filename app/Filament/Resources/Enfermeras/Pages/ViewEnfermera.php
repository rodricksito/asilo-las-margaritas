<?php

namespace App\Filament\Resources\Enfermeras\Pages;

use App\Filament\Resources\Enfermeras\EnfermeraResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEnfermera extends ViewRecord
{
    protected static string $resource = EnfermeraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
