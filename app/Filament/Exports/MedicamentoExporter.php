<?php

namespace App\Filament\Exports;

use App\Models\Medicamento;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MedicamentoExporter extends Exporter
{
    protected static ?string $model = Medicamento::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('nombre')
                ->label('Medicamento'),

            ExportColumn::make('presentacion')
                ->label('Presentación'),

            ExportColumn::make('sucursal.nombre')
                ->label('Sucursal'),

            ExportColumn::make('stock')
                ->label('Stock (unidades)'),

            ExportColumn::make('fecha_caducidad')
                ->label('Fecha de caducidad')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y')
                    : '—'),

            ExportColumn::make('activo')
                ->label('Estado')
                ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo'),

            ExportColumn::make('created_at')
                ->label('Fecha de registro')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i')
                    : '—'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de medicamentos se completó: '
            . number_format($export->successful_rows) . ' '
            . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('fila')->plural($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
