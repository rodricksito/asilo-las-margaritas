<?php

namespace App\Filament\Resources\Pacientes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PacienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->relationship(
                        name: 'sucursal',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn ($query) => $query->where('activa', true),
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->sucursal_id),

                Select::make('doctor_id')
                    ->label('Doctor asignado')
                    ->relationship(
                        name: 'doctor',
                        titleAttribute: 'nombre',
                        modifyQueryUsing: fn ($query) => $query->where('activo', true),
                    )
                    ->searchable()
                    ->preload()
                    ->placeholder('Sin doctor asignado')
                    ->helperText('Opcional. Puedes asignarlo después.'),

                TextInput::make('nombre')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. María González Pérez'),

                DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de nacimiento')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now()),

                DatePicker::make('fecha_ingreso')
                    ->label('Fecha de ingreso al asilo')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->maxDate(now()),

                Select::make('estado')
                    ->label('Estado')
                    ->required()
                    ->options([
                        'activo' => 'Activo',
                        'dado_de_alta' => 'Dado de alta',
                        'fallecido' => 'Fallecido',
                    ])
                    ->default('activo')
                    ->native(false),

                Textarea::make('observaciones')
                    ->label('Observaciones médicas')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Alergias, condiciones especiales, notas relevantes...')
                    ->columnSpanFull(),
            ]);
    }
}
