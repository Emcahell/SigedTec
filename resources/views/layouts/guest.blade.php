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
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-slate-800 to-slate-600">
            <!-- Institutional header -->
            <div class="text-center text-white mb-6 px-6">
                <div class="flex justify-center">
                    <div class="h-16 w-16 rounded-full bg-white/10 border border-white/30 flex items-center justify-center">
                        <svg class="h-9 w-9 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25 4.5 5.25v6c0 4.5 3.2 8.4 7.5 9.75 4.3-1.35 7.5-5.25 7.5-9.75v-6L12 2.25Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs uppercase tracking-widest text-slate-300">
                    {{ __('Institución Pública · Departamento de Soporte Técnico') }}
                </p>
                <h1 class="mt-1 text-xl font-semibold">
                    {{ __('Sistema de Gestión de Inventario y Despacho Tecnológico') }}
                </h1>
            </div>

            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-slate-300 text-center px-6">
                {{ __('Uso exclusivo del personal autorizado. Todas las acciones quedan registradas.') }}
            </p>
        </div>
    </body>
</html>
