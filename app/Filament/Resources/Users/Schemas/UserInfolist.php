<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del usuario')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Nombre completo'),

                        TextEntry::make('email')
                            ->label('Correo electrónico')
                            ->copyable(),

                        TextEntry::make('rol')
                            ->label('Rol')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'admin' => 'Administrador',
                                'recepcionista' => 'Recepcionista',
                                'doctor' => 'Doctor',
                                'enfermera' => 'Enfermera',
                                default => 'Sin rol',
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'danger',
                                'recepcionista' => 'warning',
                                'doctor' => 'info',
                                'enfermera' => 'success',
                                default => 'gray',
                            }),

                        TextEntry::make('sucursal.nombre')
                            ->label('Sucursal')
                            ->placeholder('—'),

                        TextEntry::make('created_at')
                            ->label('Fecha de creación')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i'),
                    ]),
            ]);
    }
}
