<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight inline-flex items-center gap-2">
                <x-icon-panel class="text-blue-600" />
                {{ __('Panel') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-sm text-blue-600">
                    <x-icon-clock class="text-blue-500" />
                    {{ __('Solicitudes') }}:
                    <span id="pendientes-badge" class="font-medium text-blue-700">
                        {{ $solicitudes_pendientes ?? 0 }}
                    </span>
                </span>

                <a href="{{ route('requests.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-blue-300 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <x-icon-eye class="text-blue-600" />
                    {{ __('Ver todas') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Estadísticas --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white border border-blue-100 rounded-md p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos disponibles') }}</p>
                            <p class="mt-1 text-3xl font-bold text-blue-900">{{ $disponibles }}</p>
                        </div>
                        <x-icon-check-circle class="text-green-600" />
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-md p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos asignados') }}</p>
                            <p class="mt-1 text-3xl font-bold text-blue-900">{{ $asignados }}</p>
                        </div>
                        <x-icon-computer class="text-blue-600" />
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-md p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Equipos en mantenimiento') }}</p>
                            <p class="mt-1 text-3xl font-bold text-blue-900">{{ $mantenimiento }}</p>
                        </div>
                        <x-icon-wrench class="text-yellow-500" />
                    </div>
                </div>

                <div class="bg-white border border-blue-100 rounded-md p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-blue-500 uppercase tracking-wider font-medium">{{ __('Total de equipos') }}</p>
                            <p class="mt-1 text-3xl font-bold text-blue-900">{{ $total }}</p>
                        </div>
                        <x-icon-cube class="text-blue-600" />
                    </div>
                </div>
            </div>

            {{-- Distribución por estado --}}
            <div class="mt-10 bg-white border border-blue-100 rounded-md p-5">
                <h3 class="text-sm text-blue-500 uppercase tracking-wider font-medium mb-4 inline-flex items-center gap-1.5">
                    <x-icon-chart-bar class="text-blue-600" />
                    {{ __('Distribución de equipos por estado') }}
                </h3>

                <div class="relative h-64 sm:h-72">
                    <canvas id="equipmentStatusChart"></canvas>
                </div>

                <div class="mt-4 text-xs text-blue-500 flex gap-4">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-blue-700 text-xs">{{ __('Disponible') }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="text-blue-700 text-xs">{{ __('Asignado') }}</span>
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span class="text-blue-700 text-xs">{{ __('Mantenimiento') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('equipmentStatusChart');

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const disponibles = {{ (int) $disponibles }};
            const asignados = {{ (int) $asignados }};
            const mantenimiento = {{ (int) $mantenimiento }};

            new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['{{ __('Disponible') }}', '{{ __('Asignado') }}', '{{ __('Mantenimiento') }}'],
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
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    resizeDelay: 150,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 12, family: 'Figtree' },
                                color: '#374151',
                            },
                        },
                        tooltip: { enabled: false },
                    },
                },
            });
        });
    </script>
</x-app-layout>
