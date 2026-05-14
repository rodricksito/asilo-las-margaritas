<?php

namespace App\Filament\Resources\Medicamentos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MedicamentoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sucursal.id')
                    ->label('Sucursal'),
                TextEntry::make('nombre'),
                TextEntry::make('presentacion'),
                TextEntry::make('fecha_caducidad')
                    ->date(),
                TextEntry::make('stock')
                    ->numeric(),
                IconEntry::make('activo')
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
