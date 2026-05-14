{{--
    Componente compartido del header de los PDFs.
    Usage: @include('pdfs.partials.pdf-header', ['folio' => 'SOLICITUD', 'numero' => $solicitud->id, 'sucursal' => $solicitud->paciente->sucursal])
--}}
<div class="header">
    <table class="header-table">
        <tr>
            <td class="brand-cell">
                {{-- Logo SVG inline (DomPDF no carga images externas confiablemente) --}}
                <table style="border-collapse: collapse;">
                    <tr>
                        <td style="vertical-align: middle; padding-right: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 120 120">
                                <rect x="0" y="0" width="120" height="120" rx="24" fill="#10b981"/>
                                <g transform="translate(60, 60)">
                                    <ellipse cx="0" cy="-26" rx="11" ry="22" fill="#ffffff"/>
                                    <ellipse cx="24.7" cy="-8.0" rx="11" ry="22" fill="#ffffff" transform="rotate(72 24.7 -8.0)"/>
                                    <ellipse cx="15.3" cy="21.0" rx="11" ry="22" fill="#ffffff" transform="rotate(144 15.3 21.0)"/>
                                    <ellipse cx="-15.3" cy="21.0" rx="11" ry="22" fill="#ffffff" transform="rotate(216 -15.3 21.0)"/>
                                    <ellipse cx="-24.7" cy="-8.0" rx="11" ry="22" fill="#ffffff" transform="rotate(288 -24.7 -8.0)"/>
                                    <circle cx="0" cy="0" r="13" fill="#fbbf24"/>
                                    <circle cx="0" cy="0" r="8" fill="#f59e0b"/>
                                </g>
                            </svg>
                        </td>
                        <td style="vertical-align: middle;">
                            <h1>ASILO LAS MARGARITAS</h1>
                            <div class="subtitle">
                                Sucursal: {{ $sucursal->nombre }}
                                @if($sucursal->direccion)
                                    · {{ $sucursal->direccion }}
                                @endif
                            </div>
                            @if($sucursal->telefono)
                                <div class="subtitle">Tel. {{ $sucursal->telefono }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td class="folio-box">
                <div class="folio">{{ $folio }} #{{ str_pad($numero, 5, '0', STR_PAD_LEFT) }}</div>
            </td>
        </tr>
    </table>
</div>
