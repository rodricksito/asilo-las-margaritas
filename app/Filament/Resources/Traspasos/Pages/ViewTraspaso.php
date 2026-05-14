<?php

namespace App\Filament\Resources\Traspasos\Pages;

use App\Filament\Resources\Traspasos\TraspasoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTraspaso extends ViewRecord
{
    protected static string $resource = TraspasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
