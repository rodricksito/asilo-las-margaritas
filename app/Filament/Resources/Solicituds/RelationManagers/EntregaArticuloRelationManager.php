<?php

namespace App\Filament\Resources\Solicituds\RelationManagers;

use App\Models\ArticuloPersonal;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EntregaArticuloRelationManager extends RelationManager
{
    protected static string $relationship = 'entregas';

    protected static ?string $title = 'Artículos personales';

    protected static ?string $modelLabel = 'artículo entregado';

    protected static ?string $pluralModelLabel = 'artículos entregados';

    protected static ?string $recordTitleAttribute = 'articulo.nombre';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('articulo_id')
                    ->label('Artículo')
                    ->options(fn () => ArticuloPersonal::where('activo', true)
                        ->orderBy('nombre')
                        ->pluck('nombre', 'id'))
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(1)
                    ->suffix('unidades'),

                DatePicker::make('fecha')
                    ->label('Fecha de entrega')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('articulo.nombre')
            ->columns([
                TextColumn::make('articulo.nombre')
                    ->label('Artículo')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->suffix(' u.')
                    ->alignEnd(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('fecha', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar entrega')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Registrar entrega de artículo')
                    ->using(function (array $data, RelationManager $livewire) {
                        // El paciente_id se infiere de la solicitud padre
                        $solicitud = $livewire->getOwnerRecord();

                        return $solicitud->entregas()->create([
                            'articulo_id' => $data['articulo_id'],
                            'cantidad' => $data['cantidad'],
                            'fecha' => $data['fecha'],
                            'paciente_id' => $solicitud->paciente_id,
                        ]);
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sin artículos entregados')
            ->emptyStateDescription('Si el familiar trajo objetos personales (jabón, pasta, toallas, etc.), regístralos aquí.')
            ->emptyStateIcon('heroicon-o-gift');
    }
}
