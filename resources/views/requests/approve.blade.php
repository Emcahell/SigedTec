<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            {{ __('Aprobar solicitud') }} #{{ $solicitud->id }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4">
                    <x-alert type="error">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                </div>
            @endif

            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <form method="POST" action="{{ route('requests.approve.store', $solicitud) }}" class="p-6">
                    @csrf

                    <p class="text-sm text-gray-700">
                        {{ __('Oficina:') }} <span class="font-medium text-gray-900">{{ $solicitud->office }}</span>
                        · {{ __('Categoría:') }} <span class="font-medium text-gray-900">{{ $solicitud->category?->nombre ?? '—' }}</span>
                        · {{ __('Cantidad solicitada:') }} <span class="font-medium text-gray-900">{{ $solicitud->quantity }}</span>
                    </p>

                    <p class="mt-2 text-sm text-blue-700">
                        {{ __('Seleccione exactamente :count equipo(s) disponible(s) para asignar.', ['count' => $solicitud->quantity]) }}
                    </p>

                    @if ($equipos->isEmpty())
                        <div class="mt-6 border border-dashed border-blue-200 rounded-md p-8 text-center">
                            <p class="text-sm text-blue-700">
                                {{ __('No hay equipos disponibles en esta categoría. No es posible aprobar la solicitud por el momento.') }}
                            </p>
                        </div>
                    @else
                        <div class="mt-4 border border-blue-100 rounded-md divide-y divide-blue-50">
                            @foreach ($equipos as $equipo)
                                <label class="flex items-start gap-3 p-3 cursor-pointer hover:bg-blue-50">
                                    <input type="checkbox" name="equipment_ids[]" value="{{ $equipo->id }}"
                                           @checked(in_array($equipo->id, old('equipment_ids', [])))
                                           class="mt-1 rounded border-blue-300 text-blue-700 focus:ring-blue-500">
                                    <span class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $equipo->codigo_patrimonial }}</span>
                                        — {{ $equipo->marca }} {{ $equipo->modelo }}
                                        @if ($equipo->cpu || $equipo->ram || $equipo->almacenamiento)
                                            <span class="block text-xs text-gray-500">
                                                {{ collect([$equipo->cpu, $equipo->ram, $equipo->almacenamiento])->filter()->join(' · ') }}
                                            </span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-6 flex items-center justify-end gap-4 pt-4 border-t border-blue-100">
                        <a href="{{ route('requests.show', $solicitud) }}"
                           class="text-sm text-blue-600 hover:text-blue-800 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <button type="submit" @disabled($equipos->isEmpty())
                                class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('Confirmar aprobación') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
