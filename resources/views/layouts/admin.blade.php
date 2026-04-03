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
        <link href="https://rsms.me/" rel="preconnect">
        <link href="https://rsms.me/inter/inter.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Google+Sans:opsz,wght@17..18,700&display=swap" rel="stylesheet">
        @stack('scripts.head')
        @vite(['resources/css/style.scss', 'resources/js/index.js'])
        @livewireStyles
    </head>
    <body>
        <div class="admin js-admin">
            <livewire:nav />
            <main id="main" class="admin__main">
                <div class="wrapper">
                    {{ $slot }}
                </div>
            </main>
        </div>
        @include('partials._toast')
        @livewire('wire-elements-modal')
        @livewireScripts
        @stack('scripts.body')
    </body>
</html>
