<?php

namespace App\Filament\Resources\Enfermeras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EnfermerasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('turno')
                    ->label('Turno')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'matutino' => 'warning',
                        'vespertino' => 'info',
                        'nocturno' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->placeholder('—')
                    ->toggleable(),

                IconColumn::make('activa')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->defaultSort('nombre')
            ->filters([
                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('turno')
                    ->label('Turno')
                    ->options([
                        'matutino' => 'Matutino',
                        'vespertino' => 'Vespertino',
                        'nocturno' => 'Nocturno',
                    ]),

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
