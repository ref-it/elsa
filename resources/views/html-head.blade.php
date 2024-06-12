<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
    <head>
        <title>{{ __('messages.pageTitle') }} | Studierendenrat Ilmenau</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ __('messages.pageTitle') }}">
        <meta property="og:site_name" content="Studierendenrat der TU Ilmenau">
        <meta property="og:image" content="{{ asset('elsa-og-image.jpg') }}">
        <meta property="og:description" content="Das Wahlinformationsportal für die studentischen Gremienwahlen und die Wahlen zur Promovierendenvertretung an der TU Ilmenau">
        <meta name="description" content="Das Wahlinformationsportal für die studentischen Gremienwahlen und die Wahlen zur Promovierendenvertretung an der TU Ilmenau">
        <meta name="theme-color" content="#18181b">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body x-data="{ mobileMenu: false }">