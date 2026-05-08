<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1,width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
        @stack('meta')
        <link href="https://fonts.googleapis.com" rel="preconnect">
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css2?family=Google+Sans:opsz,wght@17..18,700&family=Roboto:wdth,wght@75..100,400;75..100,500;75..100,600;75..100,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wdth,wght@75..100,400;75..100,500;75..100,600;75..100,700&display=swap" rel="stylesheet">
        @vite(['resources/css/style.scss', 'resources/js/index.js'])
        @livewireStyles
    </head>
    <body>
        <div class="guest">
            <div class="guest__visual" tabindex="-1"></div>
            <main class="guest__main">
                <div class="guest__wrapper">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @livewireScripts
        @include('partials._toast')
        @stack('scripts')
    </body>
</html>