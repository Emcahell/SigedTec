@php($solicitud->loadMissing(['user', 'category', 'items.equipment']))

<dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Solicitante') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->user?->name ?? '—' }}</dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Oficina') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->office }}</dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Categoría') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->category?->nombre ?? '—' }}</dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Cantidad solicitada') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->quantity }}</dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Estado') }}</dt>
        <dd class="mt-1 text-sm font-medium {{ $solicitud->status->textClass() }}">
            {{ $solicitud->status->label() }}
        </dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Fecha de solicitud') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->created_at?->format('d/m/Y H:i') }}</dd>
    </div>

    <div class="border-b border-blue-50 pb-2">
        <dt class="text-xs font-medium uppercase tracking-wider text-blue-600">{{ __('Última actualización') }}</dt>
        <dd class="mt-1 text-sm text-gray-900">{{ $solicitud->updated_at?->format('d/m/Y H:i') }}</dd>
    </div>
</dl>

@if ($solicitud->status === \App\Enums\RequestStatus::Rechazada && $solicitud->rejection_reason)
    <div class="mt-6 border-l-2 border-red-500 pl-4">
        <p class="text-xs font-medium uppercase tracking-wider text-red-700">{{ __('Motivo del rechazo') }}</p>
        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->rejection_reason }}</p>
    </div>
@endif

@if ($solicitud->items->isNotEmpty())
    <div class="mt-8">
        <h3 class="text-sm font-semibold text-blue-900">{{ __('Equipos asignados') }}</h3>

        <div class="mt-3">
            <x-table>
                <x-slot name="header">
                    <x-table-head>
                        <x-table-head-cell>{{ __('Código patrimonial') }}</x-table-head-cell>
                        <x-table-head-cell>{{ __('Marca') }}</x-table-head-cell>
                        <x-table-head-cell>{{ __('Modelo') }}</x-table-head-cell>
                        <x-table-head-cell>{{ __('Asignado el') }}</x-table-head-cell>
                    </x-table-head>
                </x-slot>

                @foreach ($solicitud->items as $item)
                    <x-table-row>
                        <x-table-cell class="font-medium">{{ $item->equipment?->codigo_patrimonial ?? '—' }}</x-table-cell>
                        <x-table-cell>{{ $item->equipment?->marca ?? '—' }}</x-table-cell>
                        <x-table-cell>{{ $item->equipment?->modelo ?? '—' }}</x-table-cell>
                        <x-table-cell class="text-gray-600">{{ $item->assigned_at?->format('d/m/Y H:i:s') ?? '—' }}</x-table-cell>
                    </x-table-row>
                @endforeach
            </x-table>
        </div>
    </div>
@elseif ($solicitud->isPending())
    <p class="mt-6 text-sm text-blue-600">
        {{ __('Los equipos se asignarán cuando la solicitud sea aprobada por Soporte Técnico.') }}
    </p>
@endif
