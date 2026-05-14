<?php

namespace App\Filament\Resources\Medicamentos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MedicamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->sucursal_id)
                    ->helperText('La sucursal donde se almacena este medicamento.'),

                TextInput::make('nombre')
                    ->label('Nombre del medicamento')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Metformina, Losartán, Acetaminofén'),

                TextInput::make('presentacion')
                    ->label('Presentación')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Tabletas 500mg, Jarabe 120ml, Cápsulas 250mg'),

                DatePicker::make('fecha_caducidad')
                    ->label('Fecha de caducidad')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->minDate(now()->addMonths(3))
                    ->helperText('Regla del asilo: no se aceptan medicamentos con menos de 3 meses de vida. Caducidad mínima permitida: ' . now()->addMonths(3)->format('d/m/Y') . '.'),

                TextInput::make('stock')
                    ->label('Stock inicial')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->suffix('unidades'),

                Toggle::make('activo')
                    ->label('Activo')
                    ->helperText('Los medicamentos inactivos no aparecen al crear recetas o solicitudes.')
                    ->default(true),
            ]);
    }
}
