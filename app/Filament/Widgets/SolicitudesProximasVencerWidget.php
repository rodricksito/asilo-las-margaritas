<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Solicituds\SolicitudResource;
use App\Models\Solicitud;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SolicitudesProximasVencerWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '⏰ Solicitudes próximas a vencer';

    protected static ?string $description = 'Solicitudes incompletas ordenadas por urgencia. Haz clic para ver detalles y completar.';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Solicitud::query()
                    ->pendientes()
                    ->whereNotNull('fecha_limite')
                    ->with(['paciente', 'familiar', 'medicamentos'])
                    ->orderBy('fecha_limite', 'asc')
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('id')
                    ->label('Solicitud')
                    ->prefix('#')
                    ->weight('medium'),

                TextColumn::make('paciente.nombre')
                    ->label('Paciente')
                    ->searchable(),

                TextColumn::make('familiar.nombre')
                    ->label('Familiar')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('faltantes')
                    ->label('Medicamentos faltantes')
                    ->state(function (Solicitud $record): string {
                        $faltantes = $record->medicamentos
                            ->filter(fn ($m) => $m->pivot->cantidad_recibida < $m->pivot->cantidad_solicitada)
                            ->map(fn ($m) => $m->nombre . ' (' . ($m->pivot->cantidad_solicitada - $m->pivot->cantidad_recibida) . ' u.)');

                        return $faltantes->isEmpty() ? '—' : $faltantes->implode(', ');
                    })
                    ->wrap()
                    ->color('warning'),

                TextColumn::make('fecha_limite')
                    ->label('Fecha límite')
                    ->date('d/m/Y')
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : 'warning')
                    ->weight('medium'),

                TextColumn::make('dias_restantes')
                    ->label('Tiempo restante')
                    ->state(function (Solicitud $record): string {
                        if (! $record->fecha_limite) {
                            return '—';
                        }
                        $diff = (int) now()->startOfDay()->diffInDays($record->fecha_limite, false);

                        return $diff < 0
                            ? '⚠️ Venció hace ' . abs($diff) . ' días'
                            : 'Faltan ' . $diff . ' días';
                    })
                    ->badge()
                    ->color(function (Solicitud $record) {
                        if (! $record->fecha_limite) {
                            return 'gray';
                        }
                        $diff = (int) now()->startOfDay()->diffInDays($record->fecha_limite, false);

                        return match (true) {
                            $diff < 0 => 'danger',
                            $diff <= 2 => 'warning',
                            default => 'success',
                        };
                    }),
            ])
            ->recordActions([
                Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Solicitud $record): string => SolicitudResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('🎉 ¡Sin pendientes!')
            ->emptyStateDescription('No hay solicitudes incompletas con plazo vencido o por vencer.')
            ->emptyStateIcon('heroicon-o-check-badge');
    }
}
