<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight inline-flex items-center gap-2">
                <x-icon-users class="text-blue-600" />
                {{ __('Usuarios') }}
            </h2>

            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                <x-icon-user-plus />
                {{ __('Nuevo solicitante') }}
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
                    <x-table>
                        <x-slot name="header">
                            <x-table-head>
                                <x-table-head-cell>{{ __('Nombre') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Correo electrónico') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Rol') }}</x-table-head-cell>
                                <x-table-head-cell>{{ __('Registrado el') }}</x-table-head-cell>
                                <x-table-head-cell class="w-48 text-right">{{ __('Acciones') }}</x-table-head-cell>
                            </x-table-head>
                        </x-slot>

                        @forelse ($users as $user)
                            <x-table-row>
                                <x-table-cell class="font-medium">{{ $user->name }}</x-table-cell>
                                <x-table-cell class="text-gray-600">{{ $user->email }}</x-table-cell>
                                <x-table-cell class="text-blue-700">{{ $user->role?->label() ?? '—' }}</x-table-cell>
                                <x-table-cell class="text-gray-500">{{ $user->created_at?->format('d/m/Y H:i') }}</x-table-cell>
                                <x-table-cell class="text-right">
                                    @if ($user->isSolicitante())
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                            <x-icon-pencil />
                                            {{ __('Editar') }}
                                        </a>

                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition"
                                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-user-{{ $user->id }}' }))"
                                        >
                                            <x-icon-trash />
                                            {{ __('Eliminar') }}
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">{{ __('Cuenta protegida') }}</span>
                                    @endif
                                </x-table-cell>
                            </x-table-row>
                        @empty
                            <x-table-row>
                                <x-table-cell colspan="5" class="text-center text-gray-500">
                                    {{ __('No hay usuarios registrados.') }}
                                </x-table-cell>
                            </x-table-row>
                        @endforelse
                    </x-table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($users->filter(fn ($user) => $user->isSolicitante()) as $user)
        <x-modal name="delete-user-{{ $user->id }}" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ __('Eliminar solicitante') }}
                </h2>

                <p class="mt-2 text-sm text-gray-600">
                    {{ __('¿Desea eliminar la cuenta de') }} <strong>{{ $user->name }}</strong>?
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    {{ __('También se eliminarán sus solicitudes y se liberarán los equipos que tenga asignados.') }}
                </p>

                <form method="POST" action="{{ route('users.destroy', $user) }}" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end gap-3">
                        <x-button variant="secondary"
                                  onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'delete-user-{{ $user->id }}' }))">
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
