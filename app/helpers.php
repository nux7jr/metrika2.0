<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('active_link')) {

    function active_link(string $name, string $class = 'active'): string
    {
        return Route::is($name) ? $class : '';
    }
}

if (!function_exists('permissions_roles')) {

    function permissions_roles(): array
    {
        return [
            'super-admin' => [
                'App\\Models\\User' => [
                    'view',
                    'view-any',
                    'create',
                    'update',
                    'delete',
                    'import',
                    'export',
                ],
                'App\\Models\\City' => [
                    'view',
                    'view-any',
                    'create',
                    'update',
                    'delete',
                ]
            ],
            'admin' => [
                'App\\Models\\User' => [
                    'view',
                    'view-any',
                    'create',
                    'update',
                    'delete',
                    'import',
                    'export',
                ],
                'App\\Models\\City' => [
                    'view',
                    'view-any',
                    'create',
                    'update',
                    'delete',
                ]
            ],
            'user' => [
                'App\\Models\\User' => [
                    'view',
                ],
                'App\\Models\\City' => [
                    'view',
                ],
            ]
        ];
    }
}
