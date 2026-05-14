<?php

namespace App\Filament\Resources\EntregaArticulos\Pages;

use App\Filament\Resources\EntregaArticulos\EntregaArticuloResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEntregaArticulo extends ViewRecord
{
    protected static string $resource = EntregaArticuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
