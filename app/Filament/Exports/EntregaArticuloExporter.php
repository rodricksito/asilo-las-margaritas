<?php

namespace App\Filament\Exports;

use App\Models\EntregaArticulo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EntregaArticuloExporter extends Exporter
{
    protected static ?string $model = EntregaArticulo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('paciente.nombre')
                ->label('Paciente'),

            ExportColumn::make('articulo.nombre')
                ->label('Artículo'),

            ExportColumn::make('cantidad')
                ->label('Cantidad'),

            ExportColumn::make('fecha')
                ->label('Fecha de entrega')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y')
                    : '—'),

            ExportColumn::make('solicitud_id')
                ->label('Solicitud asociada')
                ->formatStateUsing(fn ($state) => $state ? '#' . $state : '—'),

            ExportColumn::make('observaciones')
                ->label('Observaciones')
                ->formatStateUsing(fn ($state) => $state ?: '—'),

            ExportColumn::make('created_at')
                ->label('Fecha de registro')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y H:i')
                    : '—'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de entregas se completó: '
            . number_format($export->successful_rows) . ' '
            . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('fila')->plural($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
