<?php

namespace App\Filament\Resources\Solicituds\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MedicamentoSolicitudRelationManager extends RelationManager
{
    protected static string $relationship = 'medicamentos';

    protected static ?string $title = 'Medicamentos solicitados';

    protected static ?string $modelLabel = 'medicamento';

    protected static ?string $pluralModelLabel = 'medicamentos';

    protected static ?string $recordTitleAttribute = 'nombre';

    public function form(Schema $schema): Schema
    {
        // Solo se edita la cantidad recibida — el resto es de la receta original.
        return $schema
            ->components([
                TextInput::make('cantidad_solicitada')
                    ->label('Solicitado por el doctor')
                    ->disabled()
                    ->dehydrated(false)
                    ->suffix('unidades')
                    ->helperText('Este valor lo definió el doctor en la receta y no puede modificarse.'),

                TextInput::make('cantidad_recibida')
                    ->label('Cantidad recibida del familiar')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->suffix('unidades')
                    ->helperText('Actualiza este valor cuando el familiar traiga los faltantes.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Medicamento')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('presentacion')
                    ->label('Presentación')
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('pivot.cantidad_solicitada')
                    ->label('Solicitado')
                    ->numeric()
                    ->suffix(' u.')
                    ->alignEnd(),

                TextColumn::make('pivot.cantidad_recibida')
                    ->label('Recibido')
                    ->numeric()
                    ->suffix(' u.')
                    ->alignEnd(),

                TextColumn::make('faltante')
                    ->label('Estado')
                    ->state(fn ($record) => max(
                        0,
                        (int) ($record->pivot->cantidad_solicitada ?? 0)
                            - (int) ($record->pivot->cantidad_recibida ?? 0)
                    ))
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->formatStateUsing(fn ($state) => $state > 0
                        ? 'Faltan ' . $state . ' u.'
                        : '✅ Completo'),
            ])
            // Sin headerActions: los medicamentos son fijos por receta, no se pueden agregar/quitar.
            ->headerActions([])
            ->recordActions([
                EditAction::make()
                    ->label('Actualizar recibido')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn ($record) => 'Actualizar: ' . $record->nombre)
                    ->modalSubmitActionLabel('Guardar cambios')
                    ->after(function (RelationManager $livewire) {
                        // Recalcular estado de la solicitud al guardar el pivote
                        $solicitud = $livewire->getOwnerRecord();
                        $solicitud->load('medicamentos');

                        $hayFaltantes = $solicitud->tieneFaltantes();

                        $solicitud->update([
                            'estado' => $hayFaltantes ? 'incompleta' : 'completa',
                            'fecha_limite' => $hayFaltantes
                                ? ($solicitud->fecha_limite ?? now()->addDays(3))
                                : null,
                        ]);

                        Notification::make()
                            ->title('Cantidad actualizada')
                            ->body($hayFaltantes
                                ? 'La solicitud sigue incompleta — aún hay medicamentos faltantes.'
                                : '🎉 ¡La solicitud ahora está completa!')
                            ->color($hayFaltantes ? 'warning' : 'success')
                            ->send();
                    }),
            ])
            ->toolbarActions([])  // sin acciones masivas
            ->emptyStateHeading('Sin medicamentos asociados')
            ->emptyStateDescription('Esta solicitud no tiene medicamentos registrados.');
    }
}
