@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
])

@php
$variants = [
    'primary'   => 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
    'secondary' => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-indigo-500',
    'danger'    => 'bg-red-600 border-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'success'   => 'bg-green-600 border-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'outline'   => 'bg-transparent border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-indigo-500',
    'ghost'     => 'bg-transparent border-transparent text-gray-700 hover:bg-gray-100 focus:ring-indigo-500',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<button
    {{ $attributes->merge([
        'type' => $type,
        'disabled' => $disabled || $loading,
        'class' => 'inline-flex items-center justify-center rounded-md font-semibold text-xs uppercase tracking-widest shadow-sm transition ease-in-out duration-150 disabled:opacity-25 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 ' . $variantClass . ' ' . $sizeClass,
    ]) }}
>
    @if ($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    {{ $slot }}
</button>
