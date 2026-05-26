<?php

namespace App\Filament\Resources\ArticuloPersonals\Pages;

use App\Filament\Resources\ArticuloPersonals\ArticuloPersonalResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewArticuloPersonal extends ViewRecord
{
    protected static string $resource = ArticuloPersonalResource::class;

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
