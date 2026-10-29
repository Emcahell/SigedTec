<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                {{ __('Mis solicitudes') }}
            </h2>

            <a href="{{ route('requests.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Nueva solicitud') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4">
                    <x-alert type="success">{{ session('success') }}</x-alert>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4">
                    <x-alert type="error">{{ session('error') }}</x-alert>
                </div>
            @endif

            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <div class="p-6">
                    @if ($solicitudes->isEmpty())
                        <div class="border border-dashed border-blue-200 rounded-md p-10 text-center">
                            <p class="text-sm text-blue-700">
                                {{ __('Aún no ha registrado solicitudes de equipos para su oficina.') }}
                            </p>
                            <a href="{{ route('requests.create') }}"
                               class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-800 underline">
                                {{ __('Registrar la primera solicitud') }}
                            </a>
                        </div>
                    @else
                        <x-table>
                            <x-slot name="header">
                                <x-table-head>
                                    <x-table-head-cell>{{ __('N.º') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Fecha') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Oficina') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Categoría') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Cantidad') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Estado') }}</x-table-head-cell>
                                    <x-table-head-cell class="text-right">{{ __('Acciones') }}</x-table-head-cell>
                                </x-table-head>
                            </x-slot>

                            @foreach ($solicitudes as $solicitud)
                                <x-table-row>
                                    <x-table-cell class="font-medium">{{ $solicitud->id }}</x-table-cell>
                                    <x-table-cell class="text-gray-600">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</x-table-cell>
                                    <x-table-cell>{{ $solicitud->office }}</x-table-cell>
                                    <x-table-cell class="text-gray-600">{{ $solicitud->category?->nombre ?? '—' }}</x-table-cell>
                                    <x-table-cell>{{ $solicitud->quantity }}</x-table-cell>
                                    <x-table-cell>
                                        <span class="text-sm font-medium {{ $solicitud->status->textClass() }}">
                                            {{ $solicitud->status->label() }}
                                        </span>
                                    </x-table-cell>
                                    <x-table-cell class="text-right">
                                        <a href="{{ route('requests.show', $solicitud) }}"
                                           class="text-sm text-blue-600 hover:text-blue-800 underline">
                                            {{ __('Ver detalle') }}
                                        </a>
                                    </x-table-cell>
                                </x-table-row>
                            @endforeach
                        </x-table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
