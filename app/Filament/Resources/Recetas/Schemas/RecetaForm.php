<?php

namespace App\Filament\Resources\Recetas\Schemas;

use App\Models\Medicamento;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RecetaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ===== Sección 1: Información de la receta =====
                Section::make('Información de la receta')
                    ->description('Datos del paciente y doctor que emite la receta')
                    ->columns(2)
                    ->components([
                        Select::make('paciente_id')
                            ->label('Paciente')
                            ->relationship('paciente', 'nombre', fn ($query) => $query->where('estado', 'activo'))
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('doctor_id')
                            ->label('Doctor que emite')
                            ->relationship('doctor', 'nombre', fn ($query) => $query->where('activo', true))
                            ->required()
                            ->searchable()
                            ->preload(),

                        DatePicker::make('fecha')
                            ->label('Fecha de emisión')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->maxDate(now()),

                        DatePicker::make('vigencia')
                            ->label('Válida hasta')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()->addMonths(6))
                            ->minDate(now())
                            ->helperText('Después de esta fecha la receta deja de ser válida.'),

                        Textarea::make('observaciones')
                            ->label('Observaciones del médico')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Indicaciones especiales, cuidados, advertencias...')
                            ->columnSpanFull(),
                    ]),

                // ===== Sección 2: Medicamentos prescritos =====
                Section::make('Medicamentos prescritos')
                    ->description('Agrega cada medicamento con su dosis, frecuencia y duración')
                    ->components([
                        Repeater::make('medicamentos')
                            ->hiddenLabel()
                            ->schema([
                                // Fila 1: Medicamento al ancho completo
                                Select::make('medicamento_id')
                                    ->label('Medicamento')
                                    ->options(fn () => Medicamento::query()
                                        ->where('activo', true)
                                        ->get()
                                        ->mapWithKeys(fn ($m) => [$m->id => $m->nombre . ' — ' . $m->presentacion])
                                        ->toArray())
                                    ->required()
                                    ->searchable()
                                    ->columnSpanFull(),

                                // Fila 2: Dosis | Frecuencia (50% / 50%)
                                TextInput::make('dosis')
                                    ->label('Dosis')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Ej. 1 tableta, 10 ml'),

                                TextInput::make('frecuencia')
                                    ->label('Frecuencia')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Ej. Cada 8 horas, Antes de dormir'),

                                // Fila 3: Cantidad | Duración (50% / 50%)
                                TextInput::make('cantidad')
                                    ->label('Cantidad total')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('unidades')
                                    ->placeholder('60')
                                    ->helperText('Total a pedir al familiar'),

                                TextInput::make('duracion_dias')
                                    ->label('Duración del tratamiento')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('días')
                                    ->placeholder('30'),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['medicamento_id'])
                                    ? optional(Medicamento::find($state['medicamento_id']))->nombre
                                    : null
                            )
                            ->addActionLabel('+ Agregar medicamento')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->cloneable()
                            ->minItems(1)
                            ->defaultItems(1),
                    ]),
            ]);
    }
}
