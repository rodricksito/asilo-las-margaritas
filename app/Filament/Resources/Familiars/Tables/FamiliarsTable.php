<?php

namespace App\Filament\Resources\Familiars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FamiliarsTable
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

                TextColumn::make('parentesco')
                    ->label('Parentesco')
                    ->badge()
                    ->color('gray')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(parentesco) LIKE unaccent(?)", ['%' . $search . '%'])),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->copyable()
                    ->copyMessage('Teléfono copiado')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(telefono) LIKE unaccent(?)", ['%' . $search . '%'])),

                TextColumn::make('email')
                    ->label('Email')
                    ->placeholder('—')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('pacientes_count')
                    ->label('Pacientes')
                    ->counts('pacientes')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'warning')
                    ->tooltip('Cantidad de pacientes vinculados a este familiar'),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('nombre')
            ->filters([
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
