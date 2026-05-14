<?php

namespace App\Filament\Widgets;

use App\Models\Medicamento;
use App\Models\Paciente;
use App\Models\Solicitud;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KPIStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Pacientes activos
        $pacientesActivos = Paciente::where('estado', 'activo')->count();

        // Solicitudes pendientes (incompletas vigentes + vencidas)
        $pendientes = Solicitud::pendientes()->count();
        $vencidas = Solicitud::vencidas()->count();
        $vigentes = $pendientes - $vencidas;

        // Medicamentos próximos a caducar (≤ 3 meses)
        $medsPorCaducar = Medicamento::where('activo', true)
            ->whereNotNull('fecha_caducidad')
            ->whereDate('fecha_caducidad', '<=', now()->addMonths(3))
            ->whereDate('fecha_caducidad', '>=', now())
            ->count();

        // Atenciones del mes vs mes anterior
        $atencionesMes = Solicitud::whereYear('fecha', now()->year)
            ->whereMonth('fecha', now()->month)
            ->count();
        $atencionesMesAnterior = Solicitud::whereYear('fecha', now()->subMonth()->year)
            ->whereMonth('fecha', now()->subMonth()->month)
            ->count();
        $variacion = $atencionesMesAnterior > 0
            ? round((($atencionesMes - $atencionesMesAnterior) / $atencionesMesAnterior) * 100, 1)
            : 0;

        return [
            Stat::make('Pacientes activos', $pacientesActivos)
                ->description('Residentes registrados')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Solicitudes pendientes', $pendientes)
                ->description($vencidas > 0
                    ? "⚠️ {$vencidas} vencidas · {$vigentes} vigentes"
                    : ($pendientes > 0 ? 'Todas dentro del plazo' : 'Sin pendientes'))
                ->descriptionIcon($vencidas > 0
                    ? 'heroicon-m-exclamation-triangle'
                    : ($pendientes > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle'))
                ->color($vencidas > 0 ? 'danger' : ($pendientes > 0 ? 'warning' : 'success')),

            Stat::make('Medicamentos por caducar', $medsPorCaducar)
                ->description('Caducan en ≤ 3 meses')
                ->descriptionIcon('heroicon-m-clock')
                ->color($medsPorCaducar > 0 ? 'warning' : 'success'),

            Stat::make('Atenciones del mes', $atencionesMes)
                ->description($variacion >= 0
                    ? "+{$variacion}% vs mes anterior"
                    : "{$variacion}% vs mes anterior")
                ->descriptionIcon($variacion >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($variacion >= 0 ? 'success' : 'danger'),
        ];
    }
}
