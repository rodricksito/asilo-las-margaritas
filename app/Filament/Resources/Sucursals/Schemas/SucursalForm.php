<?php

namespace App\Filament\Resources\Sucursals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SucursalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Las Margaritas - Centro'),
                TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255),
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),
                Toggle::make('activa')
                    ->label('Activa')
                    ->helperText('Las sucursales inactivas no aparecen en formularios de selección')
                    ->default(true),
            ]);
    }
}