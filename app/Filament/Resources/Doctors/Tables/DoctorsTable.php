<?php

namespace App\Filament\Resources\Doctors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('cedula')
                    ->label('Cédula')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(cedula) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->copyable()
                    ->copyMessage('Cédula copiada'),

                TextColumn::make('especialidad')
                    ->label('Especialidad')
                    ->placeholder('—')
                    ->badge()
                    ->color('info'),

                TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('sucursal', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%'])))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—')
                    ->toggleable(),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('nombre')
            ->filters([
                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
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
