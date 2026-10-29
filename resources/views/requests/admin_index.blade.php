<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                {{ __('Solicitudes de equipos') }}
            </h2>

            <p class="text-sm text-blue-700">
                {{ $pendientes }} {{ __('pendiente(s) de procesamiento') }}
            </p>
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
                <div class="p-6 border-b border-blue-100">
                    <form method="GET" action="{{ route('requests.index') }}"
                          class="flex flex-col gap-4 sm:flex-row sm:items-end">
                        <div class="sm:w-64">
                            <label for="filtro-estado" class="block text-sm font-medium text-blue-900">
                                {{ __('Estado') }}
                            </label>
                            <select id="filtro-estado" name="estado"
                                    class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                <option value="" @selected($status === null)>{{ __('Todas') }}</option>
                                @foreach ($estados as $valor => $etiqueta)
                                    <option value="{{ $valor }}" @selected($status?->value === $valor)>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Filtrar') }}
                            </button>

                            <a href="{{ route('requests.index', ['estado' => '']) }}"
                               class="text-sm text-blue-600 hover:text-blue-800 underline">
                                {{ __('Limpiar') }}
                            </a>

                            <span class="text-sm text-gray-500">
                                {{ $solicitudes->count() }} {{ __('resultado(s)') }}
                            </span>
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    @if ($solicitudes->isEmpty())
                        <div class="border border-dashed border-blue-200 rounded-md p-10 text-center">
                            <p class="text-sm text-blue-700">
                                {{ __('No hay solicitudes para el estado seleccionado.') }}
                            </p>
                        </div>
                    @else
                        <x-table>
                            <x-slot name="header">
                                <x-table-head>
                                    <x-table-head-cell>{{ __('N.º') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Fecha') }}</x-table-head-cell>
                                    <x-table-head-cell>{{ __('Solicitante') }}</x-table-head-cell>
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
                                    <x-table-cell>{{ $solicitud->user?->name ?? '—' }}</x-table-cell>
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
                                            {{ $solicitud->isPending() ? __('Revisar') : __('Ver detalle') }}
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
