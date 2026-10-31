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

    <div class="min-h-screen">

        <!-- Header -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                        {{ __('Reportes de asignaciones') }}
                    </h2>
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-3 py-1.5 border border-blue-300 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        {{ __('Volver al dashboard') }}
                    </a>
                </div>
            </div>
        </header>

        <main class="py-12">

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Filtros -->
                <div class="bg-white border border-blue-100 rounded-md p-6 mb-8">
                    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-blue-900">
                                {{ __('Categoría') }}
                            </label>
                            <select id="categoria" name="categoria"
                                    class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('Todas') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(($filtros['categoria'] ?? '') === (string) $category->id)>
                                        {{ $category->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="oficina" class="block text-sm font-medium text-blue-900">
                                {{ __('Oficina') }}
                            </label>
                            <input type="text" id="oficina" name="oficina"
                                   value="{{ old('oficina', $filtros['oficina'] ?? '') }}"
                                   class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Ej. Oficina de Tesorería">
                        </div>

                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-blue-900">
                                {{ __('Fecha inicio') }}
                            </label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio"
                                   value="{{ old('fecha_inicio') }}"
                                   class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-blue-900">
                                {{ __('Fecha fin') }}
                            </label>
                            <input type="date" id="fecha_fin" name="fecha_fin"
                                   value="{{ old('fecha_fin') }}"
                                   class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                {{ __('Aplicar filtros') }}
                            </button>

                            <a href="{{ route('reports.index') }}"
                               class="text-sm text-blue-600 hover:text-blue-800 underline">
                                {{ __('Limpiar') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tabla de asignaciones -->
                <div class="bg-white border border-blue-100 rounded-md overflow-hidden">
                    @if ($asignaciones->isEmpty())
                        <div class="p-8 text-center text-blue-600">
                            <p class="text-sm">
                                {{ __('No hay asignaciones con los filtros seleccionados.') }}
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <x-table>
                                <x-slot name="header">
                                    <x-table-head>
                                        <x-table-head-cell>{{ __('N.º') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Solicitante') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Oficina') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Categoría') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Equipo') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Cantidad') }}</x-table-head-cell>
                                        <x-table-head-cell>{{ __('Fecha asignación') }}</x-table-head-cell>
                                        <x-table-head-cell class="text-right">{{ __('Acciones') }}</x-table-head-cell>
                                    </x-table-head>
                                </x-slot>

                                @foreach ($asignaciones as $item)
                                    <x-table-row>
                                        <x-table-cell class="font-medium">{{ $item->request->id }}</x-table-cell>
                                        <x-table-cell>{{ $item->request->user?->name ?? '—' }}</x-table-cell>
                                        <x-table-cell>{{ $item->request->office }}</x-table-cell>
                                        <x-table-cell class="text-gray-600">{{ $item->request->category?->nombre ?? '—' }}</x-table-cell>
                                        <x-table-cell class="font-medium">{{ $item->equipment?->codigo_patrimonial ?? '—' }}</x-table-cell>
                                        <x-table-cell>{{ $item->request->quantity }}</x-table-cell>
                                        <x-table-cell class="text-gray-500 text-sm">{{ $item->assigned_at?->format('d/m/Y H:i') }}</x-table-cell>
                                        <x-table-cell class="text-right">
                                            <a href="{{ route('requests.show', $item->request->id) }}"
                                               class="text-xs text-blue-600 hover:text-blue-800 underline">
                                                {{ __('Ver') }}
                                            </a>
                                        </x-table-cell>
                                    </x-table-row>
                                @endforeach
                            </x-table>

                            {{-- Paginación --}}
                            {{ $asignaciones->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </main>

    </div>

    </body>
</html>