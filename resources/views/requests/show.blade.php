<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-blue-900 leading-tight">
                {{ __('Solicitud') }} #{{ $solicitud->id }}
            </h2>

            <a href="{{ route('requests.mine') }}"
               class="btn-responsive px-4 py-2.5 sm:py-2 min-h-[44px] sm:min-h-0 bg-white border border-blue-200 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                {{ __('Volver') }}
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

            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    @include('requests.partials.details')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
