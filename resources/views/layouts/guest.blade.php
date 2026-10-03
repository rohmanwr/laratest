<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ruang Data') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-10 sm:px-6">
            <a href="/" class="mb-6 flex items-center gap-2.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-800 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Zm0 5L12 17l9-4.5M3 17.5l9 4.5 9-4.5" /></svg>
                </span>
                <span class="text-lg font-bold tracking-tight text-slate-950">Ruang Data</span>
            </a>
            <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
