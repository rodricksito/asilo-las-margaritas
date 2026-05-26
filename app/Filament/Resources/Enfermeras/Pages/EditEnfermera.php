<?php

namespace App\Filament\Resources\Enfermeras\Pages;

use App\Filament\Resources\Enfermeras\EnfermeraResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEnfermera extends EditRecord
{
    protected static string $resource = EnfermeraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('volver')
                ->label('Volver al listado')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
