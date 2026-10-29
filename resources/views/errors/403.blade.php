<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('Acceso Denegado') }} · {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-b from-slate-800 to-slate-600 px-6">
            <div class="w-full max-w-md bg-white shadow-xl rounded-lg overflow-hidden text-center">
                <div class="bg-red-600 px-6 py-8 text-white">
                    <div class="flex justify-center">
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="h-9 w-9" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm uppercase tracking-widest text-red-100">Error 403</p>
                    <h1 class="mt-1 text-2xl font-semibold">Acceso Denegado</h1>
                </div>

                <div class="px-6 py-6">
                    <p class="text-sm text-gray-600">
                        {{ $message ?? __('No tiene permisos para acceder a este recurso. Su rol asignado no está autorizado.') }}
                    </p>

                    @auth
                        <p class="mt-3 text-xs text-gray-500">
                            {{ __('Sesión iniciada como') }}: <span class="font-semibold">{{ auth()->user()->name }}</span>
                            ({{ auth()->user()->role?->label() }})
                        </p>

                        <div class="mt-6">
                            <a href="{{ auth()->user()->role->homePath() }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Volver a mi inicio') }}
                            </a>
                        </div>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Iniciar sesión') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <p class="mt-6 text-xs text-slate-300 text-center">
                {{ __('Sistema de Gestión de Inventario y Despacho Tecnológico') }}
            </p>
        </div>
    </body>
</html>
