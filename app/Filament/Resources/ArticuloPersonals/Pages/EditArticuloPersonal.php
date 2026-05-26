<?php

namespace App\Filament\Resources\ArticuloPersonals\Pages;

use App\Filament\Resources\ArticuloPersonals\ArticuloPersonalResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditArticuloPersonal extends EditRecord
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
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
