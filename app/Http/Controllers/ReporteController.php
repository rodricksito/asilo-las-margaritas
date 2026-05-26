<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class ReporteController extends Controller
{
    /**
     * Reporte PDF del inventario de medicamentos.
     */
    public function medicamentos(): Response
    {
        $medicamentos = Medicamento::with('sucursal')
            ->orderBy('nombre')
            ->get();

        $pdf = Pdf::loadView('pdfs.reporte-medicamentos', [
            'medicamentos' => $medicamentos,
            'fecha' => now(),
        ])->setPaper('letter', 'landscape');

        return $pdf->stream('reporte-medicamentos-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Reporte PDF de pacientes registrados.
     */
    public function pacientes(): Response
    {
        $pacientes = Paciente::with(['sucursal', 'doctor'])
            ->orderBy('nombre')
            ->get();

        $pdf = Pdf::loadView('pdfs.reporte-pacientes', [
            'pacientes' => $pacientes,
            'fecha' => now(),
        ])->setPaper('letter', 'landscape');

        return $pdf->stream('reporte-pacientes-' . now()->format('Y-m-d') . '.pdf');
    }
}
