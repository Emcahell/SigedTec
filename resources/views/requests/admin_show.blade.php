<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                {{ __('Solicitud') }} #{{ $solicitud->id }}
            </h2>

            <a href="{{ route('requests.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-blue-200 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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
                <div class="p-6">
                    @include('requests.partials.details')

                    @if ($solicitud->isPending())
                        <div class="mt-8 pt-6 border-t border-blue-100">
                            <p class="text-sm text-blue-700">
                                {{ __('Equipos disponibles en la categoría:') }}
                                <span class="font-semibold">{{ $disponibles }}</span>
                            </p>

                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <a href="{{ route('requests.approve', $solicitud) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    {{ __('Aprobar solicitud') }}
                                </a>

                                <a href="{{ route('requests.reject', $solicitud) }}"
                                   class="inline-flex items-center px-4 py-2 bg-white border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                    {{ __('Rechazar solicitud') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
