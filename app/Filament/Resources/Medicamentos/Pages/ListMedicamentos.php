<?php

namespace App\Filament\Resources\Medicamentos\Pages;

use App\Filament\Exports\MedicamentoExporter;
use App\Filament\Resources\Medicamentos\MedicamentoResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListMedicamentos extends ListRecords
{
    protected static string $resource = MedicamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exportar a Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->exporter(MedicamentoExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->fileName('reporte-medicamentos'),

            Action::make('exportar_pdf')
                ->label('Exportar a PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('reportes.medicamentos'))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
