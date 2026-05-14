<?php

namespace App\Filament\Resources\Doctors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DoctorForm
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
                    ->placeholder('Sin cuenta — no podrá iniciar sesión')
                    ->helperText('Vincula este doctor a una cuenta del sistema si necesita acceder al panel.'),

                TextInput::make('nombre')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Dr. Gregory House'),

                TextInput::make('cedula')
                    ->label('Cédula profesional')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->placeholder('Ej. GH123456'),

                TextInput::make('especialidad')
                    ->label('Especialidad')
                    ->maxLength(100)
                    ->placeholder('Ej. Geriatría, Medicina interna'),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(20),

                Toggle::make('activo')
                    ->label('Activo')
                    ->helperText('Los doctores inactivos no aparecen al asignar pacientes ni emitir recetas.')
                    ->default(true),
            ]);
    }
}
