<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class Vite extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->booting(function () {
            AliasLoader::getInstance()->alias(
                \App\Http\Services\ViteService::class,
                \Illuminate\Foundation\Vite::class
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
