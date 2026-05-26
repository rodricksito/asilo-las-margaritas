<?php

namespace App\Filament\Resources\Enfermeras\Pages;

use App\Filament\Resources\Enfermeras\EnfermeraResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEnfermera extends ViewRecord
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
            EditAction::make(),
        ];
    }
}
