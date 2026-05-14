<?php

namespace App\Filament\Resources\Pacientes\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PacientesTable
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

                TextColumn::make('edad')
                    ->label('Edad')
                    ->getStateUsing(fn ($record) => Carbon::parse($record->fecha_nacimiento)->age . ' años'),

                TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('doctor.nombre')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sin asignar')
                    ->toggleable(),

                TextColumn::make('familiares_count')
                    ->label('Contactos')
                    ->counts('familiares')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'warning')
                    ->tooltip(fn ($state) => $state == 0 ? 'Este paciente no tiene familiares de contacto' : null),

                TextColumn::make('fecha_ingreso')
                    ->label('Ingresó')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('estado')
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
            ])
            ->defaultSort('nombre')
            ->filters([
                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('doctor')
                    ->relationship('doctor', 'nombre')
                    ->label('Doctor')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'dado_de_alta' => 'Dado de alta',
                        'fallecido' => 'Fallecido',
                    ]),
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
