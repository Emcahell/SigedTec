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

        <!-- Tailwind -->
        <script src="https://cdn.tailwindcss.com"></script>

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Figtree', ...tailwind.config.theme.fontFamily.sans],
                        },
                    },
                },
            };
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-100">

    <x-app-layout>

        <!-- Header -->
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-blue-600">
                        {{ __('Solicitudes') }}: 
                        <span id="pendientes-badge" class="font-medium text-blue-700">
                            {{ isset($solicitudes_pendientes) ? $solicitudes_pendientes : '0' }}
                        </span>
                    </span>

                    <a href="{{ route('requests.index') }}"
                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        {{ __('Ver todas') }}
                    </a>
                </div>
            </div>
        </x-slot>

        <main class="py-12">

            <!-- Statistics Cards Section -->
            @if (isset($disponibles) && isset($asignados) && isset($mantenimiento))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-white border border-blue-100 rounded-md p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos disponibles') }}</p>
                                <p class="mt-1 text-3xl font-bold text-blue-900">{{ $disponibles }}</p>
                            </div>
                            <div class="mt-2">
                                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path class="stroke-width-2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm14-6h-2.286c.53-.12 1-.75 1-1.5V4.384c0-.37-.13-.75-.38-1.09L21.62 3.06a1.5 1.5 0 00-1.5-.83H7.38a1.5 1.5 0 00-1.5.83L5.38 12.31a1.5 1.5 0 00-.38 1.09V8.5c0 .75.47 1.38 1-1.5H5.286c-.53.12-1 .75-1 1.5v4c0 .85.68 1.5 1.5 1.5h5.57c.11 0 .23-.01.35-.02l3.036-1.838zM12 9v2m0-6v2m.88-1.238a7.962 7.962 0 01-.76-1.475A7.946 7.946 0 0112 2.072a7.95 7.95 0 017.847 7.847c-.11.06-.23.09-.35.09L12 9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-md p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos asignados') }}</p>
                                <p class="mt-1 text-3xl font-bold text-blue-900">{{ $asignados }}</p>
                            </div>
                            <div class="mt-2">
                                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path class="stroke-width-2" stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm-9.17-2.45a8.987 8.987 0 01-.11-1.53c-.34.03-.68.06-.99.1a7.96 7.96 0 01-1.81-.277A8.033 8.033 0 0112 2.072a8.026 8.026 0 01-6.073 4.875L12 21z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-md p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos en mantenimiento') }}</p>
                                <p class="mt-1 text-3xl font-bold text-blue-900">{{ $mantenimiento }}</p>
                            </div>
                            <div class="mt-2">
                                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path class="stroke-width-2" stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 011.71-3.01l7.15-5.06a2 2 0 012.32 0L22.5 10.13a2 2 0 01.96 1.77l-7.05 5.06a2 2 0 01-1.96.67l-7.15-5.05a2 2 0 01-1.71-3.02L10.29 3.86zM9 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-md p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Total de equipos') }}</p>
                                <p class="mt-1 text-3xl font-bold text-blue-900">{{ $total }}</p>
                            </div>
                            <div class="mt-2">
                                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path class="stroke-width-2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3-3m-3.5 3.5a2.5 2.5 0 110-5 2.5 2.5 0 011.5 3.37L7 14.5l4.29 2.6a1.5 1.5 0 001.55-2.43L14 8l2.61 1.66a2.5 2.5 0 111.19 3.24L14 8l.55.32a2.5 2.5 0 01-2.55 3.34L7 16.5l4.96-3.03L7 15l4.93 3.02a2.5 2.5 0 111.18 3.33L7 16l2.75.83z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif

            <!-- Chart Section -->
            @if (isset($disponibles) && isset($asignados) && isset($mantenimiento))
            <div class="mt-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white border border-blue-100 rounded-md p-5">
                    <h3 class="text-sm text-blue-500 uppercase tracking-wider font-medium mb-4">
                        {{ __('Distribución de equipos por estado') }}
                    </h3>

                    <canvas id="equipmentStatusChart" height="100"></canvas>

                    <div class="mt-4 text-xs text-blue-500 flex gap-4">
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <span class="text-blue-700 text-xs">Disponible</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-blue-700 text-xs">Asignado</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            <span class="text-blue-700 text-xs">Mantenimiento</span>
                        </span>
                    </div>
                </div>
            </div>
            @endif

        </main>

    </x-app-layout>

    <script>
        // Initialize chart when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('equipmentStatusChart').getContext('2d');

            // Check if we have chart data
            const disponibles = {{ isset($disponibles) ? $disponibles : 0 }};
            const asignados = {{ isset($asignados) ? $asignados : 0 }};
            const mantenimiento = {{ isset($mantenimiento) ? $mantenimiento : 0 }};

            if (disponibles > 0 || asignados > 0 || mantenimiento > 0) {
                const chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Disponible', 'Asignado', 'Mantenimiento'],
                        datasets: [{
                            data: [disponibles, asignados, mantenimiento],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.5)',
                                'rgba(96, 165, 250, 0.5)',
                                'rgba(251, 191, 36, 0.5)',
                            ],
                            borderColor: [
                                'rgba(34, 197, 94, 1)',
                                'rgba(96, 165, 250, 1)',
                                'rgba(251, 191, 36, 1)',
                            ],
                            borderWidth: 1,
                            borderRadius: 4,
                            cutoutPercentage: 60,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12,
                                        family: 'Figtree',
                                    },
                                    color: '#374151',
                                },
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                        },
                    }
                });
            }
        });
    </script>

    </body>
</html>