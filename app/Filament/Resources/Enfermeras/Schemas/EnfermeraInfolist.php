<?php

namespace App\Filament\Resources\Enfermeras\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EnfermeraInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sucursal.id')
                    ->label('Sucursal'),
                TextEntry::make('usuario.name')
                    ->label('Usuario')
                    ->placeholder('-'),
                TextEntry::make('nombre'),
                TextEntry::make('turno'),
                TextEntry::make('telefono')
                    ->placeholder('-'),
                IconEntry::make('activa')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
