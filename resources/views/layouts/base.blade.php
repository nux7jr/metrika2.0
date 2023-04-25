<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('page.title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
    <div id="app" class="app">
        {{-- for admin --}}
        <metrikamenu v-if='@json(Auth::check()
        && Auth::user()->hasRole("super-admin")
        && Auth::user()->two_factor_code < 1)' role="user" :is_auth='@json(Auth::check())' :user='@json(Auth::user()->login ?? '')'></metrikamenu>

        {{-- for user --}}
        <usermenu v-if='@json(Auth::check()
        && Auth::user()->hasRole("user")
        && Auth::user()->two_factor_code < 1)' role="user" :is_auth='@json(Auth::check())' :user='@json(Auth::user()->login ?? '')'></usermenu>

        {{-- CONTENT --}}
            <div class="container">
                <metrikaheader v-if='@json(Auth::check())' :is_auth='@json(Auth::check())' :user='@json(Auth::user()->login ?? '')'></metrikaheader>
                <div class="main-page">
                    @yield('content')
                </div>
            </div>
    </div>
    {{-- // $user = Auth::user();
    // $user->assignRole('super-admin'); --}}
</body>
</html>


