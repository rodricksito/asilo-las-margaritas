<?php

namespace App\Filament\Resources\EntregaArticulos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EntregaArticulosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paciente.nombre')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('articulo.nombre')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->suffix(' u.'),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('solicitud_id')
                    ->label('Solicitud')
                    ->prefix('#')
                    ->toggleable(),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                SelectFilter::make('paciente')
                    ->relationship('paciente', 'nombre')
                    ->label('Paciente')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('articulo')
                    ->relationship('articulo', 'nombre')
                    ->label('Artículo')
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
