<?php

namespace App\Filament\Resources\Solicituds\Pages;

use App\Filament\Resources\Solicituds\SolicitudResource;
use App\Models\ArticuloPersonal;
use App\Models\EntregaArticulo;
use App\Models\Familiar;
use App\Models\Paciente;
use App\Models\Receta;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\HtmlString;

class CreateSolicitud extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = SolicitudResource::class;

    /**
     * Datos extraídos del form que NO son columnas de la tabla solicitudes.
     * Se llenan en mutateFormDataBeforeCreate y se usan en afterCreate.
     */
    protected array $medicamentosData = [];
    protected array $entregasData = [];

    protected function getSteps(): array
    {
        return [
            // ============================================
            // PASO 1 — Datos generales
            // ============================================
            Step::make('Datos generales')
                ->description('Paciente, familiar y receta')
                ->icon('heroicon-o-user-circle')
                ->schema([
                    Select::make('paciente_id')
                        ->label('Paciente')
                        ->options(fn () => Paciente::where('estado', 'activo')
                            ->orderBy('nombre')
                            ->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (callable $set) {
                            // Al cambiar paciente, se invalida lo dependiente
                            $set('familiar_id', null);
                            $set('receta_id', null);
                            $set('medicamentos_recibidos', []);
                        }),

                    Select::make('familiar_id')
                        ->label('Familiar que entrega')
                        ->options(fn ($get) => $get('paciente_id')
                            ? Familiar::whereHas(
                                'pacientes',
                                fn ($q) => $q->where('pacientes.id', $get('paciente_id'))
                            )
                                ->where('activo', true)
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id')
                            : [])
                        ->required()
                        ->searchable()
                        ->disabled(fn ($get) => ! $get('paciente_id'))
                        ->helperText('Solo familiares vinculados al paciente seleccionado'),

                    Select::make('enfermera_id')
                        ->label('Enfermera que recibe')
                        ->relationship(
                            'enfermera',
                            'nombre',
                            fn ($query) => $query->where('activa', true)
                        )
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('receta_id')
                        ->label('Receta a surtir')
                        ->options(function ($get) {
                            if (! $get('paciente_id')) {
                                return [];
                            }

                            return Receta::with('doctor')
                                ->where('paciente_id', $get('paciente_id'))
                                ->where('vigencia', '>=', now())
                                ->orderByDesc('fecha')
                                ->get()
                                ->mapWithKeys(fn ($r) => [
                                    $r->id => 'Receta #' . $r->id .
                                        ' — ' . optional($r->doctor)->nombre .
                                        ' — ' . $r->fecha->format('d/m/Y'),
                                ])
                                ->toArray();
                        })
                        ->required()
                        ->searchable()
                        ->live()
                        ->disabled(fn ($get) => ! $get('paciente_id'))
                        ->helperText('Solo se muestran recetas vigentes')
                        ->afterStateUpdated(function ($state, callable $set) {
                            // Al elegir receta, autopoblamos el repeater del paso 2
                            if (! $state) {
                                $set('medicamentos_recibidos', []);

                                return;
                            }

                            $receta = Receta::with('medicamentos')->find($state);
                            if (! $receta) {
                                $set('medicamentos_recibidos', []);

                                return;
                            }

                            $meds = $receta->medicamentos->map(fn ($m) => [
                                'medicamento_id' => $m->id,
                                'medicamento_nombre' => $m->nombre . ' — ' . $m->presentacion,
                                'cantidad_solicitada' => $m->pivot->cantidad ?? 0,
                                'cantidad_recibida' => 0,
                            ])->toArray();

                            $set('medicamentos_recibidos', $meds);
                        }),

                    DatePicker::make('fecha')
                        ->label('Fecha de la solicitud')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->default(now())
                        ->maxDate(now()),
                ])
                ->columns(2),

            // ============================================
            // PASO 2 — Medicamentos recibidos
            // ============================================
            Step::make('Medicamentos recibidos')
                ->description('¿Cuánto trajo el familiar?')
                ->icon('heroicon-o-beaker')
                ->schema([
                    Placeholder::make('aviso_paso2')
                        ->hiddenLabel()
                        ->content(fn ($get) => $get('receta_id')
                            ? new HtmlString(
                                'Captura cuántas unidades trajo el familiar de cada medicamento. ' .
                                'Si trae menos de lo solicitado, se generará una solicitud incompleta ' .
                                'con plazo de <strong>3 días</strong> para completarla.'
                            )
                            : new HtmlString(
                                '<span style="color:#dc2626"><strong>⚠️ Selecciona una receta en el paso anterior antes de continuar.</strong></span>'
                            )),

                    Repeater::make('medicamentos_recibidos')
                        ->hiddenLabel()
                        ->schema([
                            // Campos ocultos que cargan desde la receta
                            Hidden::make('medicamento_id'),
                            Hidden::make('medicamento_nombre'),
                            Hidden::make('cantidad_solicitada'),

                            // Display: nombre del medicamento (fila completa)
                            Placeholder::make('label_medicamento')
                                ->label('Medicamento')
                                ->content(fn ($get) => $get('medicamento_nombre') ?? '—')
                                ->columnSpanFull(),

                            // Display: cuánto pidió el doctor
                            Placeholder::make('label_solicitada')
                                ->label('Solicitado por el doctor')
                                ->content(fn ($get) => ($get('cantidad_solicitada') ?? 0) . ' unidades'),

                            // Input: cuánto trajo el familiar
                            TextInput::make('cantidad_recibida')
                                ->label('Recibido del familiar')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->suffix('unidades')
                                ->default(0)
                                ->live(onBlur: true)
                                ->helperText(function ($state, $get) {
                                    $sol = (int) ($get('cantidad_solicitada') ?? 0);
                                    $rec = (int) ($state ?? 0);

                                    if ($sol === 0) {
                                        return null;
                                    }
                                    if ($rec >= $sol) {
                                        return '✅ Completo';
                                    }
                                    if ($rec === 0) {
                                        return 'Captura la cantidad que trajo el familiar';
                                    }

                                    return '⚠️ Faltan ' . ($sol - $rec) . ' unidades';
                                }),
                        ])
                        ->columns(2)
                        ->itemLabel(fn (array $state): ?string => $state['medicamento_nombre'] ?? null)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->collapsible()
                        ->defaultItems(0),
                ]),

            // ============================================
            // PASO 3 — Artículos personales
            // ============================================
            Step::make('Artículos personales')
                ->description('Objetos que entrega el familiar')
                ->icon('heroicon-o-gift')
                ->schema([
                    Placeholder::make('aviso_paso3')
                        ->hiddenLabel()
                        ->content(new HtmlString(
                            'Registra los artículos personales (jabón, pasta de dientes, toallas, etc.) ' .
                            'que el familiar entregó para el paciente. <em>Esta sección es opcional</em> ' .
                            '— si no trajo artículos, deja la lista vacía y avanza al siguiente paso.'
                        )),

                    Repeater::make('entregas_articulos')
                        ->hiddenLabel()
                        ->schema([
                            Select::make('articulo_id')
                                ->label('Artículo')
                                ->options(fn () => ArticuloPersonal::where('activo', true)
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id'))
                                ->required()
                                ->searchable()
                                ->columnSpan(2),

                            TextInput::make('cantidad')
                                ->label('Cantidad')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->suffix('unidades')
                                ->columnSpan(1),
                        ])
                        ->columns(3)
                        ->itemLabel(fn (array $state): ?string => isset($state['articulo_id'])
                            ? optional(ArticuloPersonal::find($state['articulo_id']))->nombre
                            : null)
                        ->addActionLabel('+ Agregar artículo')
                        ->collapsible()
                        ->minItems(0)
                        ->defaultItems(0),
                ]),

            // ============================================
            // PASO 4 — Confirmación
            // ============================================
            Step::make('Confirmación')
                ->description('Revisa antes de guardar')
                ->icon('heroicon-o-check-circle')
                ->schema([
                    Placeholder::make('resumen')
                        ->hiddenLabel()
                        ->content(function ($get) {
                            $paciente = $get('paciente_id')
                                ? optional(Paciente::find($get('paciente_id')))->nombre
                                : '—';
                            $familiar = $get('familiar_id')
                                ? optional(Familiar::find($get('familiar_id')))->nombre
                                : '—';

                            $meds = $get('medicamentos_recibidos') ?? [];
                            $totalSol = collect($meds)->sum('cantidad_solicitada');
                            $totalRec = collect($meds)->sum('cantidad_recibida');
                            $hayFaltantes = $totalRec < $totalSol;

                            $entregas = $get('entregas_articulos') ?? [];
                            $totalArticulos = collect($entregas)->sum('cantidad');
                            $cantTipos = count($entregas);

                            $html = '<div style="font-size:0.95rem;line-height:1.8">';
                            $html .= '<div style="margin-bottom:1rem">';
                            $html .= "<strong>Paciente:</strong> {$paciente}<br>";
                            $html .= "<strong>Familiar:</strong> {$familiar}<br>";
                            $html .= "<strong>Medicamentos:</strong> {$totalRec} / {$totalSol} unidades recibidas<br>";
                            $html .= "<strong>Artículos personales:</strong> {$cantTipos} tipos · {$totalArticulos} unidades";
                            $html .= '</div>';

                            if ($hayFaltantes) {
                                $faltan = $totalSol - $totalRec;
                                $limite = now()->addDays(3)->format('d/m/Y');
                                $html .= '<div style="color:#92400e;background:#fef3c7;padding:0.875rem;border-radius:0.5rem;border-left:4px solid #f59e0b">';
                                $html .= '<strong>⚠️ Solicitud incompleta</strong><br>';
                                $html .= "Faltan {$faltan} unidades de medicamento.<br>";
                                $html .= "Se establecerá fecha límite: <strong>{$limite}</strong> (3 días).";
                                $html .= '</div>';
                            } else {
                                $html .= '<div style="color:#166534;background:#dcfce7;padding:0.875rem;border-radius:0.5rem;border-left:4px solid #16a34a">';
                                $html .= '<strong>✅ Solicitud completa</strong><br>';
                                $html .= 'Todos los medicamentos prescritos fueron recibidos.';
                                $html .= '</div>';
                            }
                            $html .= '</div>';

                            return new HtmlString($html);
                        }),

                    Textarea::make('observaciones')
                        ->label('Observaciones (opcional)')
                        ->placeholder('Notas adicionales sobre la entrega, condición de los medicamentos, etc.')
                        ->rows(3)
                        ->maxLength(1000),
                ]),
        ];
    }

    /**
     * Antes de crear: extraemos arrays que no son columnas y calculamos estado/fecha_limite.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->medicamentosData = $data['medicamentos_recibidos'] ?? [];
        $this->entregasData = $data['entregas_articulos'] ?? [];

        unset($data['medicamentos_recibidos'], $data['entregas_articulos']);

        // Calcula estado en base a faltantes
        $hayFaltantes = collect($this->medicamentosData)
            ->some(fn ($m) => (int) ($m['cantidad_recibida'] ?? 0) < (int) ($m['cantidad_solicitada'] ?? 0));

        $data['estado'] = $hayFaltantes ? 'incompleta' : 'completa';
        $data['fecha_limite'] = $hayFaltantes ? now()->addDays(3) : null;

        return $data;
    }

    /**
     * Después de crear la solicitud: registramos los pivotes y entregas.
     */
    protected function afterCreate(): void
    {
        $solicitud = $this->record;

        // Pivote medicamento_solicitud (cantidades solicitada vs recibida)
        foreach ($this->medicamentosData as $med) {
            if (empty($med['medicamento_id'])) {
                continue;
            }
            $solicitud->medicamentos()->attach($med['medicamento_id'], [
                'cantidad_solicitada' => (int) ($med['cantidad_solicitada'] ?? 0),
                'cantidad_recibida' => (int) ($med['cantidad_recibida'] ?? 0),
            ]);
        }

        // Entregas de artículos personales
        foreach ($this->entregasData as $entrega) {
            if (empty($entrega['articulo_id'])) {
                continue;
            }
            EntregaArticulo::create([
                'solicitud_id' => $solicitud->id,
                'articulo_id' => $entrega['articulo_id'],
                'paciente_id' => $solicitud->paciente_id,
                'cantidad' => (int) ($entrega['cantidad'] ?? 1),
                'fecha' => $solicitud->fecha,
            ]);
        }
    }
}
