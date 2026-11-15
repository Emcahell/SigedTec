<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                {{ __('Solicitud') }} #{{ $solicitud->id }}
            </h2>

            <a href="{{ route('requests.index') }}"
               class="btn-responsive px-4 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0 bg-white border border-blue-200 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Volver al listado') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10">
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
                <div class="p-4 sm:p-6">
                    @include('requests.partials.details')

                    @if ($solicitud->isPending())
                        <div class="mt-8 pt-6 border-t border-blue-100">
                            <p class="text-sm text-blue-700">
                                {{ __('Equipos disponibles en la categoría:') }}
                                <span class="font-semibold">{{ $disponibles }}</span>
                            </p>

                            <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                                <a href="{{ route('requests.approve', $solicitud) }}"
                                   class="inline-flex w-full items-center justify-center px-4 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0 sm:w-auto bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    {{ __('Aprobar solicitud') }}
                                </a>

                                <a href="{{ route('requests.reject', $solicitud) }}"
                                   class="inline-flex w-full items-center justify-center px-4 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0 sm:w-auto bg-white border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
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
