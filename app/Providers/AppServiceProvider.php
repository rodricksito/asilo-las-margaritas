<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\HtmlString;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // === BRANDING: Inyectar fuente Inter desde Google Fonts y meta tags
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
