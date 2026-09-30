<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased"
          x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" 
          x-init="$watch('darkMode', val => localStorage.setItem('dark', val))" 
          :class="{ 'dark': darkMode }">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <nav aria-label="{{ __('Choose language') }}" class="flex justify-center gap-3 py-4">
                <a href="{{ route('lang.switch', 'en') }}" lang="en" class="font-bold {{ app()->getLocale() === 'en' ? 'text-red-600' : 'text-gray-500' }}">English</a>
                <a href="{{ route('lang.switch', 'fr') }}" lang="fr" class="font-bold {{ app()->getLocale() === 'fr' ? 'text-red-600' : 'text-gray-500' }}">Français</a>
            </nav>
            {{ $slot }}
        </div>
    </body>
</html>
