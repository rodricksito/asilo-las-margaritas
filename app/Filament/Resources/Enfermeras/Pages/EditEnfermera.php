<?php

namespace App\Filament\Resources\Enfermeras\Pages;

use App\Filament\Resources\Enfermeras\EnfermeraResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEnfermera extends EditRecord
{
    protected static string $resource = EnfermeraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
