<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(name) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(query: fn ($query, $search) => $query->whereRaw("unaccent(email) LIKE unaccent(?)", ['%' . $search . '%']))
                    ->copyable()
                    ->copyMessage('Correo copiado'),

                TextColumn::make('rol')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'recepcionista' => 'Recepcionista',
                        'doctor' => 'Doctor',
                        'enfermera' => 'Enfermera',
                        default => 'Sin rol',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'recepcionista' => 'warning',
                        'doctor' => 'info',
                        'enfermera' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('sucursal', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%'])))
                    ->sortable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('rol')
                    ->label('Rol')
                    ->options([
                        'admin' => 'Administrador',
                        'recepcionista' => 'Recepcionista',
                        'doctor' => 'Doctor',
                        'enfermera' => 'Enfermera',
                    ]),

                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->label('Sucursal')
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
