<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="initial-scale=1,width=device-width">
        <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
        @stack('meta')
        <link href="https://rsms.me/" rel="preconnect">
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Google+Sans:opsz,wght@17..18,700&family=Roboto:wdth,wght@75..100,400;75..100,500;75..100,600;75..100,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wdth,wght@75..100,400;75..100,500;75..100,600;75..100,700&display=swap" rel="stylesheet">
        <link href="{{ env('APP_URL') }}/favicon.ico" rel="icon" type="image/x-icon">
        <link href="{{ env('APP_URL') }}/favicon.ico" rel="shortcut icon" type="image/x-icon">
        @vite(['resources/js/index.js', 'resources/css/style.scss'])
        @livewireStyles
    </head>
    <body>
        <div class="app js-app">
            <x-app.nav />
            <div class="wrapper">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
        @include('partials._toast')
        @stack('scripts')
    </body>
</html>
