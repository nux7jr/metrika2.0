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


    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/3.0.2/vue.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sortablejs@1.10.2/Sortable.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/4.0.0/vuedraggable.umd.min.js"></script>
</head>
<body>
    <div id="app" class="app">
        <metrikamenu v-if='@json(Auth::check())' :is_auth='@json(Auth::check())' :user='@json(Auth::user())'></metrikamenu>
        <div class="container">
            <metrikaheader v-if='@json(Auth::check())' :is_auth='@json(Auth::check())' :user='@json(Auth::user())'></metrikaheader>
            <div class="main-page">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
