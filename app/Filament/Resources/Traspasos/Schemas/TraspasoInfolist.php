<?php

namespace App\Filament\Resources\Traspasos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TraspasoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sucursalOrigen.id')
                    ->label('Sucursal origen'),
                TextEntry::make('sucursalDestino.id')
                    ->label('Sucursal destino'),
                TextEntry::make('medicamento.id')
                    ->label('Medicamento'),
                TextEntry::make('usuario.name')
                    ->label('Usuario')
                    ->placeholder('-'),
                TextEntry::make('cantidad')
                    ->numeric(),
                TextEntry::make('fecha')
                    ->date(),
                TextEntry::make('estado'),
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
