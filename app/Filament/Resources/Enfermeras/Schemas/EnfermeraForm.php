<?php

namespace App\Filament\Resources\Enfermeras\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EnfermeraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sucursal_id')
                    ->label('Sucursal')
                    ->relationship('sucursal', 'nombre', fn ($query) => $query->where('activa', true))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->sucursal_id),

                Select::make('usuario_id')
                    ->label('Cuenta de usuario (opcional)')
                    ->relationship('usuario', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Sin cuenta — no podrá iniciar sesión'),

                TextInput::make('nombre')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Lourdes Martínez'),

                Select::make('turno')
                    ->label('Turno')
                    ->required()
                    ->options([
                        'matutino' => 'Matutino',
                        'vespertino' => 'Vespertino',
                        'nocturno' => 'Nocturno',
                    ])
                    ->default('matutino')
                    ->native(false),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),

                Toggle::make('activa')
                    ->label('Activa')
                    ->helperText('Las enfermeras inactivas no aparecen al registrar solicitudes.')
                    ->default(true),
            ]);
    }
}
