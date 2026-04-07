<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1,width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
        @stack('meta')
        <link href="https://rsms.me/" rel="preconnect">
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
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