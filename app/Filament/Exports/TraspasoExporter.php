<?php

namespace App\Filament\Exports;

use App\Models\Traspaso;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TraspasoExporter extends Exporter
{
    protected static ?string $model = Traspaso::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('medicamento.nombre')
                ->label('Medicamento'),

            ExportColumn::make('cantidad')
                ->label('Cantidad'),

            ExportColumn::make('sucursalOrigen.nombre')
                ->label('Sucursal origen'),

            ExportColumn::make('sucursalDestino.nombre')
                ->label('Sucursal destino'),

            ExportColumn::make('fecha')
                ->label('Fecha del traspaso')
                ->formatStateUsing(fn ($state) => $state
                    ? \Carbon\Carbon::parse($state)->format('d/m/Y')
                    : '—'),

            ExportColumn::make('estado')
                ->label('Estado')
                ->formatStateUsing(fn ($state) => ucfirst($state ?? '—')),

            ExportColumn::make('usuario.name')
                ->label('Registrado por')
                ->formatStateUsing(fn ($state) => $state ?: '—'),

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
        $body = 'La exportación de traspasos se completó: '
            . number_format($export->successful_rows) . ' '
            . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' '
                . str('fila')->plural($failedRowsCount) . ' fallaron.';
        }

        return $body;
    }
}
