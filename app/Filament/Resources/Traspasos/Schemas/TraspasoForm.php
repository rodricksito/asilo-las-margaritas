<?php

namespace App\Filament\Resources\Traspasos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TraspasoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sucursal_origen_id')
                    ->label('Sucursal origen')
                    ->relationship('sucursalOrigen', 'nombre', fn ($query) => $query->where('activa', true))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->sucursal_id)
                    ->live(),

                Select::make('sucursal_destino_id')
                    ->label('Sucursal destino')
                    ->relationship(
                        'sucursalDestino',
                        'nombre',
                        fn ($query, $get) => $query->where('activa', true)
                            ->when($get('sucursal_origen_id'), fn ($query, $origenId) => $query->where('id', '!=', $origenId)),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->helperText('No puede ser la misma que la sucursal origen.'),

                Select::make('medicamento_id')
                    ->label('Medicamento a transferir')
                    ->relationship('medicamento', 'nombre', fn ($query) => $query->where('activo', true))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('cantidad')
                    ->label('Cantidad')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix('unidades'),

                DatePicker::make('fecha')
                    ->label('Fecha del traspaso')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()),

                Select::make('estado')
                    ->label('Estado')
                    ->required()
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completado' => 'Completado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('pendiente')
                    ->native(false),

                Select::make('usuario_id')
                    ->label('Registrado por')
                    ->relationship('usuario', 'name')
                    ->default(fn () => auth()->id())
                    ->searchable()
                    ->preload(),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
