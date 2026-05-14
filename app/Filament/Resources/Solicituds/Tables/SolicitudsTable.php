<?php

namespace App\Filament\Resources\Solicituds\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SolicitudsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Solicitud #')
                    ->prefix('#')
                    ->sortable(),

                TextColumn::make('paciente.nombre')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('familiar.nombre')
                    ->label('Entregó')
                    ->placeholder('—')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'completa' => 'success',
                        'incompleta' => 'warning',
                        'vencida' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('fecha_limite')
                    ->label('Vence')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : null)
                    ->description(function ($state) {
                        if (! $state) return null;
                        $diff = (int) now()->diffInDays(Carbon::parse($state), false);
                        return $diff < 0 ? 'Venció hace ' . abs($diff) . ' días' : 'Faltan ' . $diff . ' días';
                    }),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'completa' => 'Completa',
                        'incompleta' => 'Incompleta',
                        'vencida' => 'Vencida',
                    ]),

                SelectFilter::make('paciente')
                    ->relationship('paciente', 'nombre')
                    ->label('Paciente')
                    ->searchable()
                    ->preload(),

                Filter::make('faltantes')
                    ->label('Con faltantes')
                    ->query(fn (Builder $q) => $q->where('estado', 'incompleta'))
                    ->toggle(),

                Filter::make('vencidas_proximas')
                    ->label('Vencen pronto (≤ 1 día)')
                    ->query(fn (Builder $q) => $q->where('estado', 'incompleta')
                        ->whereDate('fecha_limite', '<=', now()->addDay()))
                    ->toggle(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
