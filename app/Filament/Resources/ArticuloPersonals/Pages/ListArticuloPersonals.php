<?php

namespace App\Filament\Resources\ArticuloPersonals\Pages;

use App\Filament\Resources\ArticuloPersonals\ArticuloPersonalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticuloPersonals extends ListRecords
{
    protected static string $resource = ArticuloPersonalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
