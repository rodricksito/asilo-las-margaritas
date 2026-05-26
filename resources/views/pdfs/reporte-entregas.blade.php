<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Entregas de Artículos</title>
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
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #e5e7eb; font-size: 7pt; color: #9ca3af; text-align: center; }
        .resumen { margin-top: 12px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 8px 12px; font-size: 8.5pt; color: #166534; }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="brand">
                <h1>ASILO LAS MARGARITAS</h1>
                <div class="subtitle">Control de artículos personales</div>
            </td>
            <td class="meta">
                Generado el {{ $fecha->format('d/m/Y H:i') }}<br>
                Total de registros: {{ $entregas->count() }}
            </td>
        </tr>
    </table>
</div>

<div class="doc-title">Reporte de entregas de artículos</div>

<table class="data">
    <thead>
        <tr>
            <th style="width: 5%;" class="center">#</th>
            <th style="width: 26%;">Paciente</th>
            <th style="width: 24%;">Artículo</th>
            <th style="width: 10%;" class="num">Cantidad</th>
            <th style="width: 14%;" class="center">Fecha</th>
            <th style="width: 21%;">Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($entregas as $i => $entrega)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="bold">{{ $entrega->paciente?->nombre ?? '—' }}</td>
                <td>{{ $entrega->articulo?->nombre ?? '—' }}</td>
                <td class="num">{{ number_format($entrega->cantidad) }} u.</td>
                <td class="center">{{ $entrega->fecha ? \Carbon\Carbon::parse($entrega->fecha)->format('d/m/Y') : '—' }}</td>
                <td>{{ $entrega->observaciones ?: '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="center" style="padding: 16px; color: #9ca3af;">
                    No hay entregas registradas.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="resumen">
    <strong>Resumen:</strong>
    {{ $entregas->count() }} entregas en total ·
    {{ $entregas->sum('cantidad') }} unidades entregadas ·
    {{ $entregas->pluck('paciente_id')->unique()->count() }} pacientes distintos
</div>

<div class="footer">
    Asilo Las Margaritas — Reporte generado automáticamente por el sistema · {{ $fecha->format('d/m/Y H:i') }}
</div>

</body>
</html>
