<?php

namespace App\Filament\Resources\Recetas\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RecetaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la receta')
                    ->columns(2)
                    ->components([
                        TextEntry::make('id')
                            ->label('Receta #')
                            ->prefix('#')
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('vigencia')
                            ->label('Estado')
                            ->badge()
                            ->color(fn ($state) => Carbon::parse($state)->isPast() ? 'danger' : 'success')
                            ->formatStateUsing(fn ($state) =>
                                Carbon::parse($state)->isPast()
                                    ? 'Vencida ' . Carbon::parse($state)->format('d/m/Y')
                                    : 'Vigente hasta ' . Carbon::parse($state)->format('d/m/Y')
                            ),

                        TextEntry::make('paciente.nombre')
                            ->label('Paciente'),

                        TextEntry::make('doctor.nombre')
                            ->label('Doctor que emite'),

                        TextEntry::make('fecha')
                            ->label('Fecha de emisión')
                            ->date('d/m/Y'),

                        TextEntry::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Sin observaciones')
                            ->columnSpanFull(),
                    ]),

                Section::make('Medicamentos prescritos')
                    ->components([
                        RepeatableEntry::make('medicamentos')
                            ->label('')
                            ->columns(5)
                            ->schema([
                                TextEntry::make('nombre')
                                    ->label('Medicamento')
                                    ->weight('medium')
                                    ->columnSpan(2),

                                TextEntry::make('pivot.dosis')
                                    ->label('Dosis')
                                    ->badge()
                                    ->color('info'),

                                TextEntry::make('pivot.frecuencia')
                                    ->label('Frecuencia'),

                                TextEntry::make('pivot.cantidad')
                                    ->label('Cantidad')
                                    ->badge()
                                    ->color('gray')
                                    ->suffix(' u.'),
                            ]),
                    ]),
            ]);
    }
}
