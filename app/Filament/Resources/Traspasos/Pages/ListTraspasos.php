<?php

namespace App\Filament\Resources\Traspasos\Pages;

use App\Filament\Resources\Traspasos\TraspasoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTraspasos extends ListRecords
{
    protected static string $resource = TraspasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
