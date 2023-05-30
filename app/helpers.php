<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('active_link')) {
    /**
     * @param string $name
     * @param string $class
     * @return string
     */
    function active_link(string $name, string $class = 'active'): string
    {
        return Route::is($name) ? $class : '';
    }
}

if (!function_exists('getStageName')){
    /**
     * @return array
     */
    function getStageName(): array{
        return [

        ];
    }
}

if (!function_exists('phoneFormatter')){
    /**
     * @param string $phone
     * @return string
     */
    function phoneFormatter(string $phone): string{
        $phone = trim($phone);
        $phone = str_replace(array('(',' ','-',')','+'),'',$phone);
        $phone[0] === '8' ? $phone[0] = '7' : null;
        $phone = '+'.$phone;

        return preg_replace(
            array(
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
                '/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
                '/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',
            ),
            array(
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4-$5',
                '+7 ($2) $3-$4',
                '+7 ($2) $3-$4',
            ),
            $phone
        );
    }
}

if (!function_exists('permissions_roles')) {
    /**
     * @return array[]
     */
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

if (!function_exists('getUserMenu')) {

    function getUserMenu(?App\Models\User $user, bool $verifyed): ?string
    {
        if ($user === null){
            return null;
        }
        if (!$verifyed){
            return null;
        }
        return $user->roles->first()->name;
    }
}
