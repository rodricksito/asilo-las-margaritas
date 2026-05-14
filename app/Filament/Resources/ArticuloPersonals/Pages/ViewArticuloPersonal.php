<?php

namespace App\Filament\Resources\ArticuloPersonals\Pages;

use App\Filament\Resources\ArticuloPersonals\ArticuloPersonalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArticuloPersonal extends ViewRecord
{
    protected static string $resource = ArticuloPersonalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
