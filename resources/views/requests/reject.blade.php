<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            {{ __('Rechazar solicitud') }} #{{ $solicitud->id }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <form method="POST" action="{{ route('requests.reject.store', $solicitud) }}" class="p-6">
                    @csrf

                    <p class="text-sm text-gray-700">
                        {{ __('Oficina:') }} <span class="font-medium text-gray-900">{{ $solicitud->office }}</span>
                        · {{ __('Categoría:') }} <span class="font-medium text-gray-900">{{ $solicitud->category?->nombre ?? '—' }}</span>
                        · {{ __('Cantidad solicitada:') }} <span class="font-medium text-gray-900">{{ $solicitud->quantity }}</span>
                    </p>

                    <div class="mt-6">
                        <label for="rejection_reason" class="block text-sm font-medium text-blue-900">
                            {{ __('Motivo del rechazo') }}
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4" maxlength="500"
                                  class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                  required>{{ old('rejection_reason') }}</textarea>
                        <p class="mt-1 text-xs text-blue-600">
                            {{ __('El solicitante verá este motivo en el detalle de su solicitud.') }}
                        </p>
                        <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-4 pt-4 border-t border-blue-100">
                        <a href="{{ route('requests.show', $solicitud) }}"
                           class="text-sm text-blue-600 hover:text-blue-800 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-red-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                            {{ __('Confirmar rechazo') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
