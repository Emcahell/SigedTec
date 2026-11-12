@props([
    'type' => 'info',
    'dismissible' => false,
])

@php
$types = [
    'success' => 'bg-green-100 border-green-400 text-green-700',
    'error'   => 'bg-red-100 border-red-400 text-red-700',
    'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
    'info'    => 'bg-blue-100 border-blue-400 text-blue-700',
];

$icons = [
    'success' => 'check-circle',
    'error'   => 'error',
    'warning' => 'warning',
    'info'    => 'info',
];

$typeClass = $types[$type] ?? $types['info'];
$icon = $icons[$type] ?? $icons['info'];
@endphp

<div {{ $attributes->merge(['class' => 'relative rounded-lg border p-4 ' . $typeClass]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <x-dynamic-component :component="'icon-' . $icon" />
        </div>
        <div class="ml-3">
            {{ $slot }}
        </div>
    </div>

    @if ($dismissible)
        <button
            type="button"
            class="absolute top-0 right-0 mt-2 mr-2 opacity-70 hover:opacity-100 focus:outline-none focus:opacity-100 transition"
            onclick="this.closest('div').remove()"
            aria-label="{{ __('Cerrar') }}"
        >
            <x-icon-x-mark />
        </button>
    @endif
</div>
