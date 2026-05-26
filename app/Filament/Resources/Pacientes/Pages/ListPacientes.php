<?php

namespace App\Filament\Resources\Pacientes\Pages;

use App\Filament\Exports\PacienteExporter;
use App\Filament\Resources\Pacientes\PacienteResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListPacientes extends ListRecords
{
    protected static string $resource = PacienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exportar a Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->exporter(PacienteExporter::class)
                ->formats([ExportFormat::Xlsx])
                ->fileName('reporte-pacientes'),

            Action::make('exportar_pdf')
                ->label('Exportar a PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('reportes.pacientes'))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
