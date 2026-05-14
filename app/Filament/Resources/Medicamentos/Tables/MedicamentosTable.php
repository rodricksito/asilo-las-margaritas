<?php

namespace App\Filament\Resources\Medicamentos\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MedicamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Medicamento')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('presentacion')
                    ->label('Presentación')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('fecha_caducidad')
                    ->label('Caducidad')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        Carbon::parse($state)->isPast() => 'danger',
                        Carbon::parse($state)->lessThan(now()->addDays(30)) => 'danger',
                        Carbon::parse($state)->lessThan(now()->addMonths(3)) => 'warning',
                        default => 'success',
                    })
                    ->description(function ($state) {
                        $diff = (int) now()->diffInDays(Carbon::parse($state), false);
                        if ($diff < 0) {
                            return 'Caducó hace ' . abs($diff) . ' días';
                        }
                        if ($diff === 0) {
                            return 'Caduca hoy';
                        }
                        return 'Faltan ' . $diff . ' días';
                    }),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state < 10 => 'danger',
                        $state < 30 => 'warning',
                        default => 'success',
                    })
                    ->suffix(' u.'),

                IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->defaultSort('fecha_caducidad', 'asc')
            ->filters([
                SelectFilter::make('sucursal')
                    ->relationship('sucursal', 'nombre')
                    ->label('Sucursal')
                    ->searchable()
                    ->preload(),

                Filter::make('proximos_caducar')
                    ->label('Próximos a caducar (≤ 90 días)')
                    ->query(fn (Builder $query) => $query->where('fecha_caducidad', '<=', now()->addMonths(3)))
                    ->toggle(),

                Filter::make('stock_bajo')
                    ->label('Stock bajo (< 10)')
                    ->query(fn (Builder $query) => $query->where('stock', '<', 10))
                    ->toggle(),

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
