<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Traspasos</title>
    <style>
        @page { margin: 1.2cm 1.2cm 1cm 1.2cm; }
        * { box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9pt; color: #1f2937; margin: 0; padding: 0; }
        .header { border-bottom: 3px solid #10b981; padding-bottom: 10px; margin-bottom: 16px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .brand h1 { color: #10b981; margin: 0; font-size: 18pt; letter-spacing: 1px; }
        .brand .subtitle { color: #6b7280; font-size: 8pt; margin-top: 2px; }
        .meta { text-align: right; vertical-align: top; font-size: 8pt; color: #6b7280; }
        .doc-title { text-align: center; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin: 4px 0 14px 0; color: #1f2937; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 4px; }
        table.data th { background: #d1fae5; color: #065f46; text-align: left; padding: 6px 8px; border: 1px solid #6ee7b7; font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.3px; }
        table.data td { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 8.5pt; }
        table.data tr:nth-child(even) td { background: #f9fafb; }
        .center { text-align: center; }
        .num { text-align: right; font-variant-numeric: tabular-nums; }
        .bold { font-weight: bold; color: #111827; }
        .arrow { color: #10b981; font-weight: bold; }
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #e5e7eb; font-size: 7pt; color: #9ca3af; text-align: center; }
        .resumen { margin-top: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 12px; font-size: 8.5pt; color: #166534; }
        .badge { padding: 2px 8px; border-radius: 3px; font-size: 7.5pt; font-weight: bold; }
        .badge-completado { background: #d1fae5; color: #065f46; }
        .badge-pendiente { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="brand">
                <h1>ASILO LAS MARGARITAS</h1>
                <div class="subtitle">Control de inventario entre sucursales</div>
            </td>
            <td class="meta">
                Generado el {{ $fecha->format('d/m/Y H:i') }}<br>
                Total de registros: {{ $traspasos->count() }}
            </td>
        </tr>
    </table>
</div>

<div class="doc-title">Reporte de traspasos</div>

<table class="data">
    <thead>
        <tr>
            <th style="width: 5%;" class="center">#</th>
            <th style="width: 20%;">Medicamento</th>
            <th style="width: 9%;" class="num">Cantidad</th>
            <th style="width: 30%;">Movimiento</th>
            <th style="width: 12%;" class="center">Fecha</th>
            <th style="width: 12%;" class="center">Estado</th>
            <th style="width: 12%;">Registró</th>
        </tr>
    </thead>
    <tbody>
        @forelse($traspasos as $i => $traspaso)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="bold">{{ $traspaso->medicamento?->nombre ?? '—' }}</td>
                <td class="num">{{ number_format($traspaso->cantidad) }} u.</td>
                <td>
                    {{ $traspaso->sucursalOrigen?->nombre ?? '—' }}
                    <span class="arrow">→</span>
                    {{ $traspaso->sucursalDestino?->nombre ?? '—' }}
                </td>
                <td class="center">{{ $traspaso->fecha ? \Carbon\Carbon::parse($traspaso->fecha)->format('d/m/Y') : '—' }}</td>
                <td class="center">
                    @if($traspaso->estado === 'completado')
                        <span class="badge badge-completado">Completado</span>
                    @else
                        <span class="badge badge-pendiente">{{ ucfirst($traspaso->estado ?? 'Pendiente') }}</span>
                    @endif
                </td>
                <td>{{ $traspaso->usuario?->name ?? '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="center" style="padding: 16px; color: #9ca3af;">
                    No hay traspasos registrados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="resumen">
    <strong>Resumen:</strong>
    {{ $traspasos->count() }} traspasos en total ·
    {{ $traspasos->sum('cantidad') }} unidades movidas ·
    {{ $traspasos->where('estado', 'completado')->count() }} completados
</div>

<div class="footer">
    Asilo Las Margaritas — Reporte generado automáticamente por el sistema · {{ $fecha->format('d/m/Y H:i') }}
</div>

</body>
</html>
