<?php

namespace App\Filament\Resources\Sucursals\Pages;

use App\Filament\Resources\Sucursals\SucursalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSucursal extends ViewRecord
{
    protected static string $resource = SucursalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
