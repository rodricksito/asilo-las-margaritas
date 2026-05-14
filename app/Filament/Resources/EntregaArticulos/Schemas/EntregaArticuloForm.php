<?php

namespace App\Filament\Resources\EntregaArticulos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EntregaArticuloForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('paciente_id')
                    ->label('Paciente')
                    ->relationship('paciente', 'nombre', fn ($query) => $query->where('estado', 'activo'))
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('articulo_id')
                    ->label('Artículo personal')
                    ->relationship('articulo', 'nombre', fn ($query) => $query->where('activo', true))
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('solicitud_id')
                    ->label('Solicitud asociada')
                    ->relationship('solicitud', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Solicitud #{$record->id} — " . $record->fecha->format('d/m/Y'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('cantidad')
                    ->label('Cantidad recibida')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->suffix('unidades'),

                DatePicker::make('fecha')
                    ->label('Fecha de entrega')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->maxDate(now()),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
