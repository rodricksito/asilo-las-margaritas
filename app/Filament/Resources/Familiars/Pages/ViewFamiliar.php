<?php

namespace App\Filament\Resources\Familiars\Pages;

use App\Filament\Resources\Familiars\FamiliarResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFamiliar extends ViewRecord
{
    protected static string $resource = FamiliarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
