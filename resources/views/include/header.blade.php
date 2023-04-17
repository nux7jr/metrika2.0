<div class="header-background">
    <div class="container">
        <nav>
            <div>
                <a href="{{ route('home') }}">
                    {{ config('app.name') }}
                </a>
                
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link {{ active_link('home') }}" aria-current="page"
                                href="{{ route('home') }}">
                                {{ __('Главная') }}
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                        @if (Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" {{ active_link('logout') }} aria-current="page"
                                    href="{{ route('logout') }}">
                                    {{ __('Выход') }}
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link {{ active_link('register') }}" aria-current="page"
                                href="{{ route('register') }}">
                                {{ __('Регистрация') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_link('login') }}" aria-current="page"
                                    href="{{ route('login') }}">
                                    {{ __('Вход') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
