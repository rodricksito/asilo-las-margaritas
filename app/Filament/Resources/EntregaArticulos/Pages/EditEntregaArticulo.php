<?php

namespace App\Filament\Resources\EntregaArticulos\Pages;

use App\Filament\Resources\EntregaArticulos\EntregaArticuloResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEntregaArticulo extends EditRecord
{
    protected static string $resource = EntregaArticuloResource::class;

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
