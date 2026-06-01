<?php

namespace App\Filament\Resources\Sucursals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SucursalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->sortable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(direccion) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(telefono) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->toggleable(),
                IconColumn::make('activa')
                    ->label('Activa')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nombre')
            ->filters([
                TernaryFilter::make('activa')
                    ->label('Estado')
                    ->placeholder('Todas')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),
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