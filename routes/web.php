<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\SolicitudPdfController;

Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/solicitud/{solicitud}', [SolicitudPdfController::class, 'solicitud'])->name('solicitud');
    Route::get('/comprobante/{solicitud}', [SolicitudPdfController::class, 'comprobante'])->name('comprobante');
});

use App\Http\Controllers\ReporteController;

Route::middleware(['auth'])->prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/medicamentos', [ReporteController::class, 'medicamentos'])->name('medicamentos');
    Route::get('/pacientes', [ReporteController::class, 'pacientes'])->name('pacientes');
});