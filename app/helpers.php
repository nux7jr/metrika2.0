<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('active_link')) {

    function active_link(string $name, string $class = 'active'): string
    {
        return Route::is($name) ? $class : '';
    }
}
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
if (!function_exists('permissions_roles')) {

    function permissions_roles(): array
    {
        return [
            'admin' => [
                'App\\Models\\User' => [
                    'view',
                    'view-any',
                    'create',
                    'update',
                    'delete',
                    'import',
                    'export',
                ]
            ],
            'App\\Models\\User' => [
                'user' => [
                    'view',
                    'view-any',
                ]
            ]
        ];
    }
}
