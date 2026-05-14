<?php

namespace App\Filament\Resources\Solicituds\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SolicitudForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('paciente_id')
                    ->label('Paciente')
                    ->relationship('paciente', 'nombre', fn ($query) => $query->where('estado', 'activo'))
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('familiar_id')
                    ->label('Familiar que entrega')
                    ->relationship('familiar', 'nombre', fn ($query) => $query->where('activo', true))
                    ->searchable()
                    ->preload()
                    ->placeholder('Selecciona quién trajo los medicamentos'),

                Select::make('enfermera_id')
                    ->label('Enfermera que recibe')
                    ->relationship('enfermera', 'nombre', fn ($query) => $query->where('activa', true))
                    ->searchable()
                    ->preload(),

                Select::make('receta_id')
                    ->label('Receta')
                    ->relationship('receta', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Receta #{$record->id} — " . optional($record->paciente)->nombre . " — " . $record->fecha->format('d/m/Y'))
                    ->required()
                    ->searchable()
                    ->preload(),

                DateTimePicker::make('fecha')
                    ->label('Fecha y hora de la visita')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y H:i')
                    ->default(now()),

                Select::make('estado')
                    ->label('Estado')
                    ->required()
                    ->options([
                        'completa' => 'Completa',
                        'incompleta' => 'Incompleta',
                        'vencida' => 'Vencida',
                    ])
                    ->default('incompleta')
                    ->native(false)
                    ->live(),

                DatePicker::make('fecha_limite')
                    ->label('Fecha límite (3 días)')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()->addDays(3))
                    ->visible(fn ($get) => $get('estado') === 'incompleta')
                    ->helperText('Si está incompleta, el familiar tiene 3 días para completar la entrega.'),

                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(3)
                    ->maxLength(1000)
                    ->placeholder('Notas sobre la solicitud, faltantes específicos, comentarios...')
                    ->columnSpanFull(),
            ]);
    }
}
