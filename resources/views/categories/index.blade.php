<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight inline-flex items-center gap-2">
                <x-icon-tag class="text-blue-600" />
                {{ __('Categorías') }}
            </h2>

            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <x-icon-plus />
                {{ __('Nueva categoría') }}
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
                <div class="p-6">
                    <x-table>
                        <x-slot name="header">
                            <x-table-head>
                                <x-table-head-cell>{{ __('Nombre') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Descripción') }}</x-table-head-cell>
                                <x-table-head-cell class="w-48 text-right">{{ __('Acciones') }}</x-table-head-cell>
                            </x-table-head>
                        </x-slot>

                        @forelse ($categories as $category)
                            <x-table-row>
                                <x-table-cell class="font-medium">
                                    {{ $category->nombre }}
                                </x-table-cell>
                                <x-table-cell class="text-gray-500">
                                    {{ $category->descripcion ?? '—' }}
                                </x-table-cell>
                                <x-table-cell class="text-right">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        <x-icon-pencil />
                                        {{ __('Editar') }}
                                    </a>

                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-category-{{ $category->id }}' }))"
                                    >
                                        <x-icon-trash />
                                        {{ __('Eliminar') }}
                                    </button>
                                </x-table-cell>
                            </x-table-row>
                        @empty
                            <x-table-row>
                                <x-table-cell colspan="3" class="text-center text-gray-500">
                                    {{ __('No hay categorías registradas.') }}
                                </x-table-cell>
                            </x-table-row>
                        @endforelse
                    </x-table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($categories as $category)
        <x-modal name="delete-category-{{ $category->id }}" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Eliminar categoría') }}
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    {{ __('¿Desea eliminar la categoría') }} <strong>{{ $category->nombre }}</strong>?
                    {{ __('El registro se marcará como eliminado y dejará de estar disponible para nuevos equipos.') }}
                </p>

                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-3">
                        <x-button variant="secondary"
                                  onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-category-{{ $category->id }}' }))">
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
