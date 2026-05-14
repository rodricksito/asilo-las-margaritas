<?php

namespace App\Filament\Resources\ArticuloPersonals\Pages;

use App\Filament\Resources\ArticuloPersonals\ArticuloPersonalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditArticuloPersonal extends EditRecord
{
    protected static string $resource = ArticuloPersonalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
