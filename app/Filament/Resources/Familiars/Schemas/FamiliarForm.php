<?php

namespace App\Filament\Resources\Familiars\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FamiliarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Carlos González Pérez'),

                TextInput::make('parentesco')
                    ->label('Parentesco')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Ej. Hijo, Hija, Esposo, Hermana'),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->placeholder('8711234567'),

                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->maxLength(255)
                    ->placeholder('correo@ejemplo.com'),

                TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
