<?php

namespace App\Filament\Resources\Recetas\Tables;

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

class RecetasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Receta #')
                    ->prefix('#')
                    ->sortable(),

                TextColumn::make('paciente.nombre')
                    ->label('Paciente')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('paciente', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%'])))
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('doctor.nombre')
                    ->label('Doctor')
                    ->searchable(query: fn ($query, $search) => $query->whereHas('doctor', fn ($q) => $q->whereRaw("unaccent(nombre) LIKE unaccent(?)", ['%' . $search . '%'])))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('fecha')
                    ->label('Emitida')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('vigencia')
                    ->label('Vigencia')
                    ->date('d/m/Y')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => Carbon::parse($state)->isPast() ? 'danger' : 'success')
                    ->formatStateUsing(function ($state) {
                        $date = Carbon::parse($state);
                        if ($date->isPast()) {
                            return 'Vencida ' . $date->format('d/m/Y');
                        }
                        return 'Vigente hasta ' . $date->format('d/m/Y');
                    }),

                TextColumn::make('medicamentos_count')
                    ->label('Medicamentos')
                    ->counts('medicamentos')
                    ->badge()
                    ->color('info')
                    ->suffix(fn ($state) => $state == 1 ? ' medicamento' : ' medicamentos'),
            ])
            ->defaultSort('fecha', 'desc')
            ->filters([
                SelectFilter::make('paciente')
                    ->relationship('paciente', 'nombre')
                    ->label('Paciente')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('doctor')
                    ->relationship('doctor', 'nombre')
                    ->label('Doctor')
                    ->searchable()
                    ->preload(),

                Filter::make('vigentes')
                    ->label('Solo vigentes')
                    ->query(fn (Builder $q) => $q->where('vigencia', '>=', now()))
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
