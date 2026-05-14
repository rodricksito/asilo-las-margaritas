<?php

namespace App\Filament\Resources\Enfermeras\Pages;

use App\Filament\Resources\Enfermeras\EnfermeraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEnfermeras extends ListRecords
{
    protected static string $resource = EnfermeraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
