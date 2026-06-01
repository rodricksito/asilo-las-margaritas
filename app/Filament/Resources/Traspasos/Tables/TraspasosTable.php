<?php

namespace App\Filament\Resources\Traspasos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TraspasosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Traspaso #')
                    ->prefix('#')
                    ->sortable(),

                TextColumn::make('medicamento.nombre')
                    ->label('Medicamento')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('medicamento', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%'])))
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->suffix(' u.'),

                TextColumn::make('sucursalOrigen.nombre')
                    ->label('Origen')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('sucursalOrigen', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%']))),

                TextColumn::make('sucursalDestino.nombre')
                    ->label('Destino')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('sucursalDestino', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%']))),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pendiente' => 'warning',
                        'completado' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('usuario.name')
                    ->label('Registró')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completado' => 'Completado',
                        'cancelado' => 'Cancelado',
                    ]),

                SelectFilter::make('sucursalOrigen')
                    ->relationship('sucursalOrigen', 'nombre')
                    ->label('Sucursal origen')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('sucursalDestino')
                    ->relationship('sucursalDestino', 'nombre')
                    ->label('Sucursal destino')
                    ->searchable()
                    ->preload(),
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
