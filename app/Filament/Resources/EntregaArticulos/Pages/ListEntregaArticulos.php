<?php

namespace App\Filament\Resources\EntregaArticulos\Pages;

use App\Filament\Exports\EntregaArticuloExporter;
use App\Filament\Resources\EntregaArticulos\EntregaArticuloResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListEntregaArticulos extends ListRecords
{
    protected static string $resource = EntregaArticuloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exportar a Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->exporter(EntregaArticuloExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->fileName('reporte-entregas'),

            Action::make('exportar_pdf')
                ->label('Exportar a PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('reportes.entregas'))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
