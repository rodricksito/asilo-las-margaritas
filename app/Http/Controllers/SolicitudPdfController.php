<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class SolicitudPdfController extends Controller
{
    /**
     * Genera la hoja de solicitud de medicamentos (regla del PDF original:
     * "Imprimir solicitud de entrada").
     */
    public function solicitud(Solicitud $solicitud): Response
    {
        $solicitud->load([
            'paciente.sucursal',
            'familiar',
            'enfermera',
            'receta.doctor',
            'receta.medicamentos',
            'medicamentos',
        ]);

        // La dosis y frecuencia viven en el pivote medicamento_receta,
        // mientras que cantidad_solicitada/recibida viven en medicamento_solicitud.
        // Indexamos los pivotes de receta por id de medicamento para acceso rápido.
        $recetaPivots = $solicitud->receta->medicamentos->keyBy('id');

        $pdf = Pdf::loadView('pdfs.solicitud-pdf', [
            'solicitud' => $solicitud,
            'recetaPivots' => $recetaPivots,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("solicitud-{$solicitud->id}.pdf");
    }

    /**
     * Genera el comprobante de artículos personales entregados
     * (regla del PDF original: "Imprimir hoja de objetos de uso personal").
     */
    public function comprobante(Solicitud $solicitud): Response
    {
        abort_if(
            ! $solicitud->entregas()->exists(),
            404,
            'Esta solicitud no tiene artículos personales registrados.'
        );

        $solicitud->load([
            'paciente.sucursal',
            'familiar',
            'enfermera',
            'entregas.articulo',
        ]);

        $pdf = Pdf::loadView('pdfs.comprobante-articulos-pdf', [
            'solicitud' => $solicitud,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("comprobante-{$solicitud->id}.pdf");
    }
}
