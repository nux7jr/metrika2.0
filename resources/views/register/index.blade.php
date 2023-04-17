@extends('layouts.auth')

@section('auth.content')
    <register />    
    {{-- <x-card>

        <x-card-header>
            <x-card-title>
                {{ __('Регистрация') }}
            </x-card-title>

            <x-slot name="right">
                <a href="{{ route('login') }}">
                    {{ __('Вход') }}
                </a>
            </x-slot>

        </x-card-header>

        <x-card-body>

            <x-form action="{{ route('register.store') }}" method="POST">

                <x-form-item>

                    <x-label required>{{ __('Имя') }}</x-label>
                    <x-input name="name" autofocus />
                    <x-error name="name" />

                </x-form-item>

                <x-form-item>

                    <x-label required>{{ __('Email') }}</x-label>
                    <x-input type="email" name="email" />
                    <x-error name="email" />

                </x-form-item>

                <x-form-item>

                    <x-label required>{{ __('Пароль') }}</x-label>
                    <x-input type="password" name="password" />
                    <x-error name="password" />

                </x-form-item>

                <x-form-item>

                    <x-label required>{{ __('Пароль еще раз') }}</x-label>
                    <x-input type="password" name="password_confirmation" />
                    <x-error name="password_confirmation" />

                </x-form-item>

                <x-form-item>

                    <x-label required>{{ __('Telegram Chat ID') }}</x-label>
                    <x-input type="text" name="telegram_chat_id" />
                    <x-error name="telegram_chat_id" />

                </x-form-item>

                <x-button type="submit">

                    {{ __('Зарегистрироваться') }}

                </x-button>

            </x-form>

        </x-card-body>

    </x-card> --}}
@endsection
