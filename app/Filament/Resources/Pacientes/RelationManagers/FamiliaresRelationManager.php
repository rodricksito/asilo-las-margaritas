<?php

namespace App\Filament\Resources\Pacientes\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FamiliaresRelationManager extends RelationManager
{
    protected static string $relationship = 'familiares';

    protected static ?string $title = 'Familiares de contacto';

    protected static ?string $modelLabel = 'familiar';

    protected static ?string $pluralModelLabel = 'familiares';

    protected static ?string $recordTitleAttribute = 'nombre';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre completo')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Carlos González Pérez'),

                TextInput::make('parentesco')
                    ->label('Parentesco')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Ej. Hijo, Hija, Esposo, Hermana, Sobrino'),

                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->placeholder('8711234567'),

                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->maxLength(255)
                    ->placeholder('correo@ejemplo.com'),

                TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('parentesco')
                    ->label('Parentesco')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->copyable()
                    ->copyMessage('Teléfono copiado'),

                TextColumn::make('email')
                    ->label('Email')
                    ->placeholder('—')
                    ->toggleable(),

                IconColumn::make('pivot.es_principal')
                    ->label('Principal')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Nuevo familiar')
                    ->modalHeading('Crear nuevo familiar')
                    ->modalSubmitActionLabel('Crear y vincular'),

                AttachAction::make()
                    ->label('Vincular existente')
                    ->modalHeading('Vincular familiar existente')
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Familiar')
                            ->placeholder('Busca por nombre...'),
                        Toggle::make('es_principal')
                            ->label('Marcar como contacto principal')
                            ->default(false),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make()
                    ->label('Desvincular')
                    ->modalHeading('Desvincular familiar')
                    ->modalDescription('El familiar no será borrado, solo se quitará la relación con este paciente.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Desvincular seleccionados'),
                ]),
            ])
            ->emptyStateHeading('Sin familiares registrados')
            ->emptyStateDescription('Agrega un nuevo familiar o vincula uno existente al paciente.');
    }
}
