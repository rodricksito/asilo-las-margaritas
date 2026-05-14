<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud #{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 1.5cm 1.5cm 1cm 1.5cm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 10pt;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header { border-bottom: 3px solid #d97706; padding-bottom: 12px; margin-bottom: 18px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .brand h1 {
            color: #d97706;
            margin: 0 0 2px 0;
            font-size: 20pt;
            letter-spacing: 1.5px;
            font-weight: bold;
        }
        .brand .subtitle { color: #6b7280; font-size: 9pt; }
        .folio-box {
            text-align: right;
            vertical-align: top;
        }
        .folio {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            padding: 8px 14px;
            display: inline-block;
            font-weight: bold;
            font-size: 11pt;
            color: #78350f;
            letter-spacing: 0.5px;
        }
        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 6px 0 18px 0;
        }

        /* Section headers */
        h2 {
            color: #d97706;
            font-size: 11pt;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin: 18px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Meta info table */
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 5px 8px; vertical-align: top; }
        .meta-table .label {
            font-weight: bold;
            color: #6b7280;
            width: 22%;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .meta-table .value { font-size: 10pt; width: 28%; }

        /* Medications table */
        .meds-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .meds-table th {
            background: #fef3c7;
            color: #78350f;
            text-align: left;
            padding: 7px 6px;
            border: 1px solid #fbbf24;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .meds-table td {
            padding: 7px 6px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 9pt;
        }
        .meds-table tr:nth-child(even) td { background: #fafafa; }
        .meds-table .med-name { font-weight: bold; color: #111827; }
        .meds-table .med-presentation { color: #6b7280; font-size: 8pt; display: block; margin-top: 2px; }
        .num { text-align: right; font-variant-numeric: tabular-nums; }
        .faltante-rojo { color: #b91c1c; font-weight: bold; }
        .faltante-cero { color: #16a34a; font-weight: bold; }

        /* Status badges */
        .badge {
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9pt;
            display: inline-block;
        }
        .badge-completa { background: #d1fae5; color: #065f46; }
        .badge-incompleta { background: #fef3c7; color: #92400e; }
        .badge-vencida { background: #fee2e2; color: #991b1b; }

        /* Alerts */
        .alerta {
            background: #fef3c7;
            border-left: 4px solid #d97706;
            padding: 9px 12px;
            margin: 12px 0;
            font-size: 9pt;
            color: #78350f;
        }
        .alerta strong { color: #78350f; }

        /* Observations */
        .observaciones-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            min-height: 30px;
            font-size: 9pt;
            color: #4b5563;
        }
        .observaciones-empty { color: #9ca3af; font-style: italic; }

        /* Signatures */
        .firmas {
            margin-top: 45px;
            width: 100%;
            border-collapse: collapse;
        }
        .firmas td {
            width: 50%;
            text-align: center;
            padding: 0 25px;
            vertical-align: top;
        }
        .firma-line {
            border-top: 1px solid #1f2937;
            padding-top: 5px;
            margin-top: 35px;
        }
        .firma-name { font-weight: bold; font-size: 9pt; color: #111827; }
        .firma-role {
            color: #6b7280;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 7pt;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <table class="header-table">
        <tr>
            <td class="brand">
                <h1>ASILO LAS MARGARITAS</h1>
                <div class="subtitle">
                    Sucursal: {{ $solicitud->paciente->sucursal->nombre }}
                    @if($solicitud->paciente->sucursal->direccion)
                        · {{ $solicitud->paciente->sucursal->direccion }}
                    @endif
                </div>
                @if($solicitud->paciente->sucursal->telefono)
                    <div class="subtitle">Tel. {{ $solicitud->paciente->sucursal->telefono }}</div>
                @endif
            </td>
            <td class="folio-box">
                <div class="folio">SOLICITUD #{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="doc-title">Solicitud de medicamentos</div>

<h2>Datos generales</h2>
<table class="meta-table">
    <tr>
        <td class="label">Paciente</td>
        <td class="value"><strong>{{ $solicitud->paciente->nombre }}</strong></td>
        <td class="label">Fecha de la solicitud</td>
        <td class="value">{{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <td class="label">Familiar que entrega</td>
        <td class="value">{{ $solicitud->familiar?->nombre ?? '—' }}</td>
        <td class="label">Enfermera que recibe</td>
        <td class="value">{{ $solicitud->enfermera?->nombre ?? '—' }}</td>
    </tr>
    <tr>
        <td class="label">Doctor prescriptor</td>
        <td class="value">{{ $solicitud->receta->doctor->nombre }}</td>
        <td class="label">Cédula profesional</td>
        <td class="value">{{ $solicitud->receta->doctor->cedula }}</td>
    </tr>
    <tr>
        <td class="label">Receta vinculada</td>
        <td class="value">
            #{{ $solicitud->receta->id }}
            <span style="color: #6b7280; font-size: 9pt;">
                · emitida {{ \Carbon\Carbon::parse($solicitud->receta->fecha)->format('d/m/Y') }}
            </span>
        </td>
        <td class="label">Vigencia de la receta</td>
        <td class="value">{{ \Carbon\Carbon::parse($solicitud->receta->vigencia)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td class="label">Estado</td>
        <td class="value">
            @if($solicitud->estado === 'completa')
                <span class="badge badge-completa">COMPLETA</span>
            @elseif($solicitud->estado === 'vencida')
                <span class="badge badge-vencida">VENCIDA</span>
            @else
                <span class="badge badge-incompleta">INCOMPLETA</span>
            @endif
        </td>
        @if($solicitud->fecha_limite && $solicitud->estado !== 'completa')
            <td class="label">Fecha límite</td>
            <td class="value">
                <strong>{{ \Carbon\Carbon::parse($solicitud->fecha_limite)->format('d/m/Y') }}</strong>
            </td>
        @else
            <td class="label">&nbsp;</td>
            <td class="value">&nbsp;</td>
        @endif
    </tr>
</table>

@if($solicitud->estado === 'incompleta' && $solicitud->fecha_limite)
    <div class="alerta">
        <strong>Aviso:</strong> El familiar tiene hasta el
        <strong>{{ \Carbon\Carbon::parse($solicitud->fecha_limite)->format('d \\d\\e F \\d\\e Y') }}</strong>
        para completar la entrega de los medicamentos faltantes.
    </div>
@endif

<h2>Medicamentos solicitados</h2>
<table class="meds-table">
    <thead>
        <tr>
            <th style="width: 28%;">Medicamento</th>
            <th style="width: 17%;">Dosis</th>
            <th style="width: 21%;">Frecuencia</th>
            <th class="num" style="width: 11%;">Solicit.</th>
            <th class="num" style="width: 11%;">Recib.</th>
            <th class="num" style="width: 12%;">Faltante</th>
        </tr>
    </thead>
    <tbody>
        @forelse($solicitud->medicamentos as $med)
            @php
                $recetaPivot = $recetaPivots[$med->id]?->pivot ?? null;
                $solicitada = $med->pivot->cantidad_solicitada;
                $recibida = $med->pivot->cantidad_recibida;
                $faltante = $solicitada - $recibida;
            @endphp
            <tr>
                <td>
                    <span class="med-name">{{ $med->nombre }}</span>
                    <span class="med-presentation">{{ $med->presentacion }}</span>
                </td>
                <td>{{ $recetaPivot?->dosis ?? '—' }}</td>
                <td>{{ $recetaPivot?->frecuencia ?? '—' }}</td>
                <td class="num">{{ $solicitada }}</td>
                <td class="num">{{ $recibida }}</td>
                <td class="num {{ $faltante > 0 ? 'faltante-rojo' : 'faltante-cero' }}">
                    {{ $faltante }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #9ca3af; padding: 16px;">
                    No hay medicamentos registrados en esta solicitud.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($solicitud->observaciones)
    <h2>Observaciones</h2>
    <div class="observaciones-box">{{ $solicitud->observaciones }}</div>
@endif

<table class="firmas">
    <tr>
        <td>
            <div class="firma-line">
                <div class="firma-name">{{ $solicitud->familiar?->nombre ?? 'Familiar responsable' }}</div>
                <div class="firma-role">Familiar que entrega</div>
            </div>
        </td>
        <td>
            <div class="firma-line">
                <div class="firma-name">{{ $solicitud->enfermera?->nombre ?? 'Enfermera de recepción' }}</div>
                <div class="firma-role">Enfermera que recibe</div>
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    Documento generado el {{ now()->format('d/m/Y H:i') }} ·
    Asilo Las Margaritas — Sistema interno de control de medicamentos
</div>

</body>
</html>
