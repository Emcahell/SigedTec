<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Solicitudes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-alert type="info">
                        {{ __('Aún no ha registrado solicitudes de equipos para su oficina.') }}
                    </x-alert>

                    <div class="mt-6 border border-dashed border-gray-300 rounded-lg p-10 text-center">
                        <p class="text-sm text-gray-500">
                            {{ __('El módulo de creación y seguimiento de solicitudes se habilitará en la siguiente fase del proyecto.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
