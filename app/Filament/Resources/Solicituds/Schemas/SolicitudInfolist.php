<?php

namespace App\Filament\Resources\Solicituds\Schemas;

use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SolicitudInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ============================================
                // Datos generales
                // ============================================
                Section::make('Datos generales')
                    ->icon('heroicon-o-identification')
                    ->columns(3)
                    ->components([
                        TextEntry::make('id')
                            ->label('Solicitud')
                            ->prefix('#')
                            ->weight('bold'),

                        TextEntry::make('fecha')
                            ->label('Fecha de captura')
                            ->dateTime('d/m/Y H:i'),

                        TextEntry::make('paciente.nombre')
                            ->label('Paciente')
                            ->weight('medium'),

                        TextEntry::make('familiar.nombre')
                            ->label('Familiar que entregó')
                            ->placeholder('—'),

                        TextEntry::make('enfermera.nombre')
                            ->label('Enfermera que recibió')
                            ->placeholder('—'),

                        TextEntry::make('receta')
                            ->label('Receta')
                            ->state(fn ($record) => $record->receta
                                ? "#{$record->receta->id} — " .
                                    optional($record->receta->doctor)->nombre .
                                    ' — ' . $record->receta->fecha->format('d/m/Y')
                                : '—'),
                    ]),

                // ============================================
                // Estado y plazo
                // ============================================
                Section::make('Estado de la solicitud')
                    ->icon('heroicon-o-flag')
                    ->columns(2)
                    ->components([
                        TextEntry::make('estado')
                            ->label('Estado actual')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'completa' => 'success',
                                'incompleta' => 'warning',
                                'vencida' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => ucfirst((string) $state)),

                        TextEntry::make('fecha_limite')
                            ->label('Fecha límite para completar')
                            ->date('d/m/Y')
                            ->placeholder('No aplica')
                            ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : null)
                            ->helperText(function ($state) {
                                if (! $state) {
                                    return null;
                                }
                                $diff = (int) now()->diffInDays(Carbon::parse($state), false);

                                return $diff < 0
                                    ? '⚠️ Venció hace ' . abs($diff) . ' días'
                                    : 'Faltan ' . $diff . ' días para vencer';
                            }),
                    ]),

                // ============================================
                // Medicamentos prescritos
                // ============================================
                Section::make('Medicamentos prescritos')
                    ->icon('heroicon-o-beaker')
                    ->description('Cantidades solicitadas por el doctor vs. cantidades recibidas del familiar')
                    ->components([
                        RepeatableEntry::make('medicamentos')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('nombre')
                                    ->label('')
                                    ->weight('bold')
                                    ->formatStateUsing(fn ($state, $record) => $record->presentacion
                                        ? $state . ' — ' . $record->presentacion
                                        : $state)
                                    ->columnSpanFull(),

                                TextEntry::make('pivot.cantidad_solicitada')
                                    ->label('Solicitado por doctor')
                                    ->suffix(' u.')
                                    ->numeric(),

                                TextEntry::make('pivot.cantidad_recibida')
                                    ->label('Recibido del familiar')
                                    ->suffix(' u.')
                                    ->numeric(),

                                TextEntry::make('faltante')
                                    ->label('Estado')
                                    ->state(fn ($record) => max(
                                        0,
                                        (int) ($record->pivot->cantidad_solicitada ?? 0)
                                            - (int) ($record->pivot->cantidad_recibida ?? 0)
                                    ))
                                    ->badge()
                                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                                    ->formatStateUsing(fn ($state) => $state > 0
                                        ? '⚠️ Faltan ' . $state . ' u.'
                                        : '✅ Completo'),
                            ])
                            ->columns(3),
                    ]),

                // ============================================
                // Artículos personales
                // ============================================
                Section::make('Artículos personales recibidos')
                    ->icon('heroicon-o-gift')
                    ->components([
                        RepeatableEntry::make('entregas')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('articulo.nombre')
                                    ->label('Artículo')
                                    ->weight('medium'),

                                TextEntry::make('cantidad')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->suffix(' u.'),

                                TextEntry::make('fecha')
                                    ->label('Fecha de entrega')
                                    ->date('d/m/Y'),
                            ])
                            ->columns(3),
                    ])
                    ->visible(fn ($record) => $record->entregas->isNotEmpty()),

                // ============================================
                // Observaciones
                // ============================================
                Section::make('Observaciones')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->components([
                        TextEntry::make('observaciones')
                            ->hiddenLabel()
                            ->placeholder('Sin observaciones')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => filled($record->observaciones)),

                // ============================================
                // Auditoría
                // ============================================
                Section::make('Auditoría')
                    ->icon('heroicon-o-clock')
                    ->columns(2)
                    ->collapsed()
                    ->components([
                        TextEntry::make('created_at')
                            ->label('Creada')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Última actualización')
                            ->dateTime('d/m/Y H:i'),
                    ]),
            ]);
    }
}
