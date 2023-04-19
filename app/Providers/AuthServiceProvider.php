<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->defineSuperAdmin();
        $this->defineAutoDiscover();
    }

    private function defineAutoDiscover(){
        Gate::guessPolicyNamesUsing(function ($class){
            return str_replace("\\Models\\","\\Policies\\", $class) . 'Policy';
        });
    }

    private function defineSuperAdmin(){
        Gate::before(function ($user){
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
