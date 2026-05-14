<?php

namespace App\Filament\Resources\EntregaArticulos\Pages;

use App\Filament\Resources\EntregaArticulos\EntregaArticuloResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEntregaArticulos extends ListRecords
{
    protected static string $resource = EntregaArticuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
