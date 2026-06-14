<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Panel UNCP') }} — Acceso</title>

        <!-- Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <script>
            (() => {
                const storedTheme = localStorage.getItem('admin-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-uncp-gray-dark antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 pt-6 sm:pt-0 bg-uncp-bg">
            <div class="text-center">
                <a href="/" class="inline-flex items-center justify-center rounded-lg bg-uncp-green p-3 shadow-sm ring-1 ring-uncp-gold/30">
                    <x-application-logo class="h-16 w-16" />
                </a>
                <p class="mt-3 text-xs font-bold uppercase tracking-widest text-uncp-green">Universidad Nacional del Centro del Perú</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 overflow-hidden rounded-lg border border-uncp-gold/30 bg-white px-6 py-5 shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
