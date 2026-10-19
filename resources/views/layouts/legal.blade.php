<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <title>{{ config('app.title') }}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <x-robots-meta />
        <meta name="theme-color" content="#18181b">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        @livewireStyles
        @fluxAppearance
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body>
        <div class="grid grid-rows-[auto_1fr] w-full h-full">
            <livewire:head.head-legal />
            <main class="public">
                {{ $slot }}
            </main>
        </div>

        <x-info />

        @livewireScripts
        @fluxScripts
    </body>
</html>