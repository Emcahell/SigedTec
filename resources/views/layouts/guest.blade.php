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
        <div class="min-h-screen flex flex-col justify-center items-center py-8 sm:py-12 px-4 bg-gradient-to-b from-slate-800 to-slate-600">
            <!-- Institutional header -->
            <div class="w-full max-w-md text-center text-white mb-6">
                <div class="flex justify-center">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-full bg-white/10 border border-white/30 flex items-center justify-center">
                        <x-application-logo class="h-8 w-8 sm:h-10 sm:w-10 fill-current text-white" />
                    </div>
                </div>
                <p class="mt-3 text-[0.65rem] sm:text-xs uppercase tracking-widest text-slate-300 leading-relaxed px-2">
                    {{ __('SigedTec · Departamento de Soporte Técnico') }}
                </p>
                <h1 class="mt-2 text-lg sm:text-xl font-semibold leading-snug px-1">
                    {{ __('Sistema de Gestión de Inventario y Despacho Tecnológico') }}
                </h1>
            </div>

            <div class="w-full max-w-md bg-white shadow-xl rounded-lg px-5 py-6 sm:px-8 sm:py-7">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-slate-300 text-center max-w-md px-2 leading-relaxed">
                {{ __('Uso exclusivo del personal autorizado. Todas las acciones quedan registradas.') }}
            </p>
        </div>
    </body>
</html>
