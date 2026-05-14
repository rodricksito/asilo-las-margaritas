<?php

namespace App\Filament\Resources\Traspasos\Pages;

use App\Filament\Resources\Traspasos\TraspasoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTraspaso extends EditRecord
{
    protected static string $resource = TraspasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
