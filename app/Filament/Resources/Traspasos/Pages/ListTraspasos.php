<?php

namespace App\Filament\Resources\Traspasos\Pages;

use App\Filament\Exports\TraspasoExporter;
use App\Filament\Resources\Traspasos\TraspasoResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListTraspasos extends ListRecords
{
    protected static string $resource = TraspasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exportar a Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->exporter(TraspasoExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->fileName('reporte-traspasos'),

            Action::make('exportar_pdf')
                ->label('Exportar a PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('reportes.traspasos'))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
