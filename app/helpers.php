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
            '1'                 => '',
            'UC_FK7L5G'         => '',
            'NEW'               => '',
            'LOSE'              => '',
            'UC_ANFHRF'         => '',
            'PREPAYMENT_INVOIC' => '',
            'PREPAYMENT_INVOI'  => '',
            'EXECUTING'         => '',
            'APOLOGY'           => '',
            'UC_X97DFD'         => '',
            'UC_BQW3GK'         => '',
            'UC_P7M5NG'         => '',
            'UC_Y7SKD4'         => '',
            'UC_JWI4PG'         => '',
            'UC_A2WWRO'         => '',
            'PREPARATION'       => '',
            'UC_M1DQYG'         => '',
            'UC_F7Q7FQ'         => '',
            'UC_90F6RO'         => '',
            'UC_ZYUW5N'         => '',
            'UC_2PPAH5'         => '',
            'UC_P9RBK0'         => '',
            'FINAL_INVOICE'     => '',
            'UC_O4SOYL'         => '',
            'UC_EDOOX9'         => '',
            'UC_SKDNES'         => '',
            'UC_Z75LXO'         => '',
            'UC_0TMR75'         => '',
            'UC_MDFV4R'         => '',
            'UC_X35L0B'         => '',
            'UC_CLB7A9'         => '',
            'UC_SJQTZH'         => '',
            'UC_52V599'         => '',
            'UC_ZYZ0JM'         => '',
            '2'                 => '',
            'UC_767C1L'         => '',
            'UC_1Q2QZ1'         => '',
            'UC_M7QAO4'         => '',
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
