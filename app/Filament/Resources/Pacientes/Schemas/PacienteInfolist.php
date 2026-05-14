<?php

namespace App\Filament\Resources\Pacientes\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PacienteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del paciente')
                    ->description('Datos generales del residente')
                    ->columns(2)
                    ->components([
                        TextEntry::make('nombre')
                            ->label('Nombre completo')
                            ->weight('bold')
                            ->size('lg')
                            ->columnSpanFull(),

                        TextEntry::make('fecha_nacimiento')
                            ->label('Fecha de nacimiento')
                            ->date('d/m/Y'),

                        TextEntry::make('edad')
                            ->label('Edad')
                            ->getStateUsing(fn ($record) => Carbon::parse($record->fecha_nacimiento)->age . ' años')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('fecha_ingreso')
                            ->label('Fecha de ingreso')
                            ->date('d/m/Y'),

                        TextEntry::make('estado')
                            ->label('Estado')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'activo' => 'success',
                                'dado_de_alta' => 'info',
                                'fallecido' => 'gray',
                                default => 'warning',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'activo' => 'Activo',
                                'dado_de_alta' => 'Dado de alta',
                                'fallecido' => 'Fallecido',
                                default => $state,
                            }),
                    ]),

                Section::make('Atención médica')
                    ->description('Sucursal y doctor responsable')
                    ->columns(2)
                    ->components([
                        TextEntry::make('sucursal.nombre')
                            ->label('Sucursal')
                            ->placeholder('Sin sucursal asignada'),

                        TextEntry::make('doctor.nombre')
                            ->label('Doctor asignado')
                            ->placeholder('Sin doctor asignado'),

                        TextEntry::make('doctor.especialidad')
                            ->label('Especialidad del doctor')
                            ->placeholder('—'),

                        TextEntry::make('doctor.cedula')
                            ->label('Cédula profesional')
                            ->placeholder('—')
                            ->copyable()
                            ->copyMessage('Cédula copiada'),
                    ]),

                Section::make('Notas médicas')
                    ->components([
                        TextEntry::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Sin observaciones registradas')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Información del registro')
                    ->columns(2)
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Registrado el')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i')
                            ->since(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
