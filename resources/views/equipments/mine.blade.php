<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight inline-flex items-center gap-2">
                <x-icon-computer class="text-blue-600" />
                {{ __('Mis equipos asignados') }}
            </h2>

            <a href="{{ route('requests.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <x-icon-plus />
                {{ __('Nueva solicitud') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <div class="p-6">
                    @if ($asignaciones->isEmpty())
                        <div class="p-8 text-center text-blue-600">
                            <p class="text-sm">
                                {{ __('Aún no tiene equipos asignados. Los equipos aparecerán aquí cuando sus solicitudes sean aprobadas.') }}
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <x-table>
                                <x-slot name="header">
                                    <x-table-head>
                                        <x-table-head-cell>{{ __('Código patrimonial') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Equipo') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Categoría') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Solicitud') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Fecha de asignación') }}</x-table-head-cell>
                                        <x-table-head-cell class="text-right">{{ __('Acciones') }}</x-table-head-cell>
                                    </x-table-head>
                                </x-slot>

                                @foreach ($asignaciones as $item)
                                    <x-table-row>
                                        <x-table-cell class="font-medium">
                                            {{ $item->equipment?->codigo_patrimonial ?? '—' }}
                                        </x-table-cell>
                                        <x-table-cell class="text-gray-600">
                                            {{ $item->equipment?->marca }} {{ $item->equipment?->modelo }}
                                        </x-table-cell>
                                        <x-table-cell class="text-gray-600">
                                            {{ $item->equipment?->category?->nombre ?? '—' }}
                                        </x-table-cell>
                                        <x-table-cell class="text-blue-700">
                                            N.º {{ $item->request_id }}
                                        </x-table-cell>
                                        <x-table-cell class="text-gray-500 text-sm">
                                            {{ $item->assigned_at?->format('d/m/Y H:i') ?? '—' }}
                                        </x-table-cell>
                                        <x-table-cell class="text-right">
                                            <a href="{{ route('requests.show', $item->request_id) }}"
                                               class="inline-flex items-center gap-1.5 text-xs text-blue-600 hover:text-blue-800 underline">
                                                <x-icon-eye />
                                                {{ __('Ver solicitud') }}
                                            </a>
                                        </x-table-cell>
                                    </x-table-row>
                                @endforeach
                            </x-table>
                        </div>

                        <div class="mt-4">
                            {{ $asignaciones->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
