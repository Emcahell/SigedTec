<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Equipos tecnológicos') }}
            </h2>

            <a href="{{ route('equipments.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Nuevo equipo') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4">
                    <x-alert type="success">
                        {{ session('success') }}
                    </x-alert>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filtros -->
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('equipments.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4 sm:items-end">
                        <div>
                            <x-input-label for="filtro-categoria" :value="__('Categoría')" />
                            <select id="filtro-categoria" name="categoria"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">{{ __('Todas') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) ($filtros['categoria'] ?? '') === (string) $category->id)>
                                        {{ $category->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="filtro-estado" :value="__('Estado')" />
                            <select id="filtro-estado" name="estado"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">{{ __('Todos') }}</option>
                                @foreach ($estados as $valor => $etiqueta)
                                    <option value="{{ $valor }}" @selected(($filtros['estado'] ?? '') === $valor)>
                                        {{ $etiqueta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <x-button variant="primary" type="submit">
                                {{ __('Filtrar') }}
                            </x-button>

                            <a href="{{ route('equipments.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs uppercase tracking-widest text-gray-700 bg-white shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Limpiar') }}
                            </a>
                        </div>

                        <div class="text-sm text-gray-500 sm:text-right">
                            {{ trans_choice(':count equipo(s) encontrado(s)', $equipments->count(), ['count' => $equipments->count()]) }}
                        </div>
                    </form>
                </div>

                <div class="p-6">
                    <x-table>
                        <x-slot name="header">
                            <x-table-head>
                                <x-table-head-cell>{{ __('Código patrimonial') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Categoría') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Marca') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Modelo') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Estado') }}</x-table-head-cell>
                                <x-table-head-cell class="w-56 text-right">{{ __('Acciones') }}</x-table-head-cell>
                            </x-table-head>
                        </x-slot>

                        @forelse ($equipments as $equipment)
                            <x-table-row>
                                <x-table-cell class="font-medium">
                                    {{ $equipment->codigo_patrimonial }}
                                </x-table-cell>
                                <x-table-cell class="text-gray-500">
                                    {{ $equipment->category?->nombre ?? '—' }}
                                </x-table-cell>
                                <x-table-cell>
                                    {{ $equipment->marca }}
                                </x-table-cell>
                                <x-table-cell>
                                    {{ $equipment->modelo }}
                                </x-table-cell>
                                <x-table-cell>
                                    <span class="px-2 py-1 text-xs font-semibold rounded {{ $equipment->estado->badgeClass() }}">
                                        {{ $equipment->estado->label() }}
                                    </span>
                                </x-table-cell>
                                <x-table-cell class="text-right">
                                    <a href="{{ route('equipments.show', $equipment) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Ver') }}
                                    </a>

                                    <a href="{{ route('equipments.edit', $equipment) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Editar') }}
                                    </a>

                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-equipment-{{ $equipment->id }}' }))"
                                    >
                                        {{ __('Eliminar') }}
                                    </button>
                                </x-table-cell>
                            </x-table-row>
                        @empty
                            <x-table-row>
                                <x-table-cell colspan="6" class="text-center text-gray-500">
                                    {{ __('No se encontraron equipos con los filtros seleccionados.') }}
                                </x-table-cell>
                            </x-table-row>
                        @endforelse
                    </x-table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($equipments as $equipment)
        <x-modal name="delete-equipment-{{ $equipment->id }}" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Eliminar equipo') }}
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    {{ __('¿Desea eliminar el equipo') }} <strong>{{ $equipment->codigo_patrimonial }}</strong>
                    ({{ $equipment->marca }} {{ $equipment->modelo }})?
                    {{ __('El registro se marcará como eliminado y quedará disponible para su restauración.') }}
                </p>

                <form method="POST" action="{{ route('equipments.destroy', $equipment) }}" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-3">
                        <x-button variant="secondary"
                                  onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-equipment-{{ $equipment->id }}' }))">
                            {{ __('Cancelar') }}
                        </x-button>

                        <x-button variant="danger" type="submit">
                            {{ __('Sí, eliminar') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach
</x-app-layout>
