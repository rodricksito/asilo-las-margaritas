<?php

namespace App\Filament\Resources\Familiars\Pages;

use App\Filament\Resources\Familiars\FamiliarResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateFamiliar extends CreateRecord
{
    protected static string $resource = FamiliarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('volver')
                ->label('Volver al listado')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),
        ];
    }
}
