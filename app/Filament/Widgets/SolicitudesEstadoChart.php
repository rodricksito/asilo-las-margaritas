<?php

namespace App\Filament\Widgets;

use App\Models\Solicitud;
use Filament\Widgets\ChartWidget;

class SolicitudesEstadoChart extends ChartWidget
{
    protected ?string $heading = 'Solicitudes por estado';

    protected ?string $description = 'Distribución actual de todas las solicitudes registradas';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $completas = Solicitud::completas()->count();
        $incompletasVigentes = Solicitud::incompletasVigentes()->count();
        $vencidas = Solicitud::vencidas()->count();

        return [
            'datasets' => [
                [
                    'data' => [$completas, $incompletasVigentes, $vencidas],
                    'backgroundColor' => [
                        '#16a34a',  // verde - completas
                        '#f59e0b',  // amarillo - incompletas vigentes
                        '#dc2626',  // rojo - vencidas
                    ],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => [
                'Completas',
                'Incompletas (vigentes)',
                'Vencidas',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
