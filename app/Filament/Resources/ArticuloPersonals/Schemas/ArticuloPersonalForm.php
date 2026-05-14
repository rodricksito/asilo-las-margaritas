<?php

namespace App\Filament\Resources\ArticuloPersonals\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ArticuloPersonalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre del artículo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Pasta de dientes, Jabón de tocador, Toalla'),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(2)
                    ->maxLength(500)
                    ->placeholder('Marca, presentación, detalles relevantes')
                    ->columnSpanFull(),

                Toggle::make('activo')
                    ->label('Activo')
                    ->helperText('Los artículos inactivos no aparecen al registrar entregas.')
                    ->default(true),
            ]);
    }
}
