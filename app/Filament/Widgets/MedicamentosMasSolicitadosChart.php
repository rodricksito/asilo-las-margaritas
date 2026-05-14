<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MedicamentosMasSolicitadosChart extends ChartWidget
{
    protected ?string $heading = 'Top 10 medicamentos más solicitados';

    protected ?string $description = 'Total de unidades pedidas a los familiares (acumulado histórico)';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'half';

    protected function getData(): array
    {
        $top = DB::table('medicamento_solicitud as ms')
            ->join('medicamentos as m', 'ms.medicamento_id', '=', 'm.id')
            ->select('m.nombre', DB::raw('SUM(ms.cantidad_solicitada) as total'))
            ->groupBy('m.id', 'm.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Unidades solicitadas',
                    'data' => $top->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $top->pluck('nombre')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',  // barras horizontales — mejor para nombres largos
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }
}
