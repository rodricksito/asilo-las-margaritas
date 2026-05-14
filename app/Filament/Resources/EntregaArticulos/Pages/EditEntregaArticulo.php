<?php

namespace App\Filament\Resources\EntregaArticulos\Pages;

use App\Filament\Resources\EntregaArticulos\EntregaArticuloResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEntregaArticulo extends EditRecord
{
    protected static string $resource = EntregaArticuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
