<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle del equipo') }}
            </h2>

            <div class="flex gap-3">
                <a href="{{ route('equipments.edit', $equipment) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Editar') }}
                </a>

                <a href="{{ route('equipments.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Volver') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded {{ $equipment->estado->badgeClass() }}">
                            {{ $equipment->estado->label() }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $equipment->category?->nombre ?? __('Sin categoría') }}
                        </span>
                    </div>

                    <dl class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Código patrimonial') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->codigo_patrimonial }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Marca') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->marca }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Modelo') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->modelo }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('CPU') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->cpu ?? '—' }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('RAM') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->ram ?? '—' }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Almacenamiento') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->almacenamiento ?? '—' }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Fecha de registro') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->created_at?->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Última actualización') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $equipment->updated_at?->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div class="border-b border-gray-100 pb-2">
                            <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Categoría') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $equipment->category?->nombre ?? __('Sin categoría') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
