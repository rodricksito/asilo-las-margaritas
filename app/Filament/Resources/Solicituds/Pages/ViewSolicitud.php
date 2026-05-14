<?php

namespace App\Filament\Resources\Solicituds\Pages;

use App\Filament\Resources\Solicituds\SolicitudResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSolicitud extends ViewRecord
{
    protected static string $resource = SolicitudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimir_solicitud')
                ->label('Imprimir solicitud')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('pdf.solicitud', $this->record))
                ->openUrlInNewTab(),

            Action::make('imprimir_comprobante')
                ->label('Comprobante de artículos')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(fn () => route('pdf.comprobante', $this->record))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->entregas()->exists()),

            EditAction::make(),
        ];
    }
}
