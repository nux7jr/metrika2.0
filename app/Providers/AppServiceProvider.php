<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('date', date('Y'));
        Schema::defaultStringLength(191);
        $vite = new Vite();

        $vite->useScriptTagAttributes([
            'defer' => true,
        ]);

        // Vite::useScriptTagAttributes([
        //     'defer' => true, // Specify an attribute without a value...
        // ]);
    }
}
