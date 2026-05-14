<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante #{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</title>
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
        .folio-box { text-align: right; vertical-align: top; }
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

        h2 {
            color: #d97706;
            font-size: 11pt;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin: 18px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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

        .articles-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .articles-table th {
            background: #fef3c7;
            color: #78350f;
            text-align: left;
            padding: 8px 8px;
            border: 1px solid #fbbf24;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .articles-table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 10pt;
        }
        .articles-table tr:nth-child(even) td { background: #fafafa; }
        .articles-table .article-name { font-weight: bold; color: #111827; }
        .num { text-align: right; font-variant-numeric: tabular-nums; }
        .center { text-align: center; }

        .legal-text {
            background: #f9fafb;
            border-left: 3px solid #d1d5db;
            padding: 12px 14px;
            margin: 22px 0;
            font-size: 9pt;
            color: #4b5563;
            line-height: 1.6;
        }

        .firmas {
            margin-top: 50px;
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

        .footer {
            margin-top: 30px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 7pt;
            color: #9ca3af;
            text-align: center;
        }

        .total-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            padding: 8px 12px;
            display: inline-block;
            margin-top: 6px;
            font-size: 10pt;
            color: #78350f;
        }
        .total-box strong { font-size: 12pt; }
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
                <div class="folio">COMPROBANTE #{{ str_pad($solicitud->id, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>
</div>

<div class="doc-title">Comprobante de artículos personales</div>

<h2>Datos de la entrega</h2>
<table class="meta-table">
    <tr>
        <td class="label">Paciente</td>
        <td class="value"><strong>{{ $solicitud->paciente->nombre }}</strong></td>
        <td class="label">Solicitud asociada</td>
        <td class="value">#{{ $solicitud->id }}</td>
    </tr>
    <tr>
        <td class="label">Familiar que entrega</td>
        <td class="value">{{ $solicitud->familiar?->nombre ?? '—' }}</td>
        <td class="label">Parentesco</td>
        <td class="value">{{ $solicitud->familiar?->parentesco ?? '—' }}</td>
    </tr>
    <tr>
        <td class="label">Enfermera que recibe</td>
        <td class="value">{{ $solicitud->enfermera?->nombre ?? '—' }}</td>
        <td class="label">Fecha de entrega</td>
        <td class="value">{{ \Carbon\Carbon::parse($solicitud->fecha)->format('d/m/Y H:i') }}</td>
    </tr>
</table>

<h2>Artículos recibidos</h2>
<table class="articles-table">
    <thead>
        <tr>
            <th style="width: 8%;" class="center">#</th>
            <th style="width: 35%;">Artículo</th>
            <th style="width: 15%;" class="center">Cantidad</th>
            <th style="width: 17%;">Fecha</th>
            <th style="width: 25%;">Observaciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($solicitud->entregas as $i => $entrega)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="article-name">{{ $entrega->articulo->nombre }}</td>
                <td class="center">{{ $entrega->cantidad }} u.</td>
                <td>{{ \Carbon\Carbon::parse($entrega->fecha)->format('d/m/Y') }}</td>
                <td>{{ $entrega->observaciones ?? '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #9ca3af; padding: 16px;">
                    No hay artículos registrados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($solicitud->entregas->isNotEmpty())
    <div class="total-box">
        Total de artículos entregados: <strong>{{ $solicitud->entregas->sum('cantidad') }}</strong>
        ({{ $solicitud->entregas->count() }} {{ $solicitud->entregas->count() === 1 ? 'tipo' : 'tipos' }} distintos)
    </div>
@endif

<div class="legal-text">
    Por medio del presente, el personal del Asilo Las Margaritas hace constar que ha recibido
    los artículos arriba descritos para uso personal del paciente <strong>{{ $solicitud->paciente->nombre }}</strong>.
    Estos artículos serán utilizados exclusivamente para los cuidados del residente y permanecerán
    bajo resguardo del personal autorizado de la institución. El presente comprobante sirve como
    constancia de entrega para el familiar responsable.
</div>

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
                <div class="firma-role">Personal que recibe</div>
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    Documento generado el {{ now()->format('d/m/Y H:i') }} ·
    Asilo Las Margaritas — Sistema interno de control de artículos personales
</div>

</body>
</html>
