<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerSqliteUnaccentFunction();
        $this->registerFilamentRenderHooks();
    }

    /**
     * Registra una funcion SQL custom `unaccent()` en la conexion SQLite.
     *
     * Esto permite escribir queries que ignoran tildes:
     *   WHERE unaccent(nombre) LIKE '%maria%'  // encuentra "María"
     *
     * SQLite no tiene soporte nativo para busqueda insensible a tildes
     * (a diferencia de MySQL con utf8mb4_0900_ai_ci o PostgreSQL con unaccent).
     * Esta funcion suple esa carencia normalizando el texto en PHP.
     */
    private function registerSqliteUnaccentFunction(): void
    {
        // Solo aplica si estamos usando SQLite
        if (DB::connection()->getDriverName() !== 'sqlite') {
            return;
        }

        $pdo = DB::connection()->getPdo();

        $pdo->sqliteCreateFunction('unaccent', function (?string $string): string {
            if ($string === null) {
                return '';
            }

            return strtolower(strtr($string, [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
                'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
                'À' => 'a', 'È' => 'e', 'Ì' => 'i', 'Ò' => 'o', 'Ù' => 'u',
                'ñ' => 'n', 'Ñ' => 'n',
                'ü' => 'u', 'Ü' => 'u',
                'ç' => 'c', 'Ç' => 'c',
            ]));
        }, 1);
    }

    /**
     * BRANDING: Inyectar fuente Inter desde Google Fonts y meta tags
     */
    private function registerFilamentRenderHooks(): void
    {
        Filament::serving(function () {
            Filament::registerRenderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => new HtmlString('
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
                    <link rel="apple-touch-icon" href="' . asset('images/branding/logo-icon.svg') . '">
                    <meta name="theme-color" content="#10b981">
                    <meta name="application-name" content="Las Margaritas">
                ')
            );
        });
    }
}
