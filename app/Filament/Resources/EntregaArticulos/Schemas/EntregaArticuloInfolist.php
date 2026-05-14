<?php

namespace App\Filament\Resources\EntregaArticulos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EntregaArticuloInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('solicitud.id')
                    ->label('Solicitud'),
                TextEntry::make('articulo.id')
                    ->label('Articulo'),
                TextEntry::make('paciente.id')
                    ->label('Paciente'),
                TextEntry::make('cantidad')
                    ->numeric(),
                TextEntry::make('fecha')
                    ->date(),
                TextEntry::make('observaciones')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
