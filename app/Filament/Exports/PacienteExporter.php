<?php

namespace App\Filament\Exports;

use App\Models\Paciente;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PacienteExporter extends Exporter
{
    protected static ?string $model = Paciente::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('nombre')
                ->label('Paciente'),

            ExportColumn::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y')
                    : '—'),

            ExportColumn::make('edad')
                ->label('Edad')
                ->state(fn (Paciente $record) => $record->fecha_nacimiento
                    ? \Carbon\Carbon::parse($record->fecha_nacimiento)->age . ' años'
                    : '—'),

            ExportColumn::make('fecha_ingreso')
                ->label('Fecha de ingreso')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y')
                    : '—'),

            ExportColumn::make('sucursal.nombre')
                ->label('Sucursal'),

            ExportColumn::make('doctor.nombre')
                ->label('Doctor asignado'),

            ExportColumn::make('estado')
                ->label('Estado')
                ->formatStateUsing(fn ($state) => ucfirst($state ?? '—')),

            ExportColumn::make('familiares_count')
                ->label('Familiares registrados')
                ->counts('familiares'),

            ExportColumn::make('created_at')
                ->label('Fecha de registro')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i')
                    : '—'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de pacientes se completó: '
            . number_format($export->successful_rows) . ' '
            . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('fila')->plural($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
