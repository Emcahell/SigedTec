@props(['active' => false])

@php
$classes = ($active ?? false)
    ? 'flex min-w-0 flex-1 flex-col items-center justify-center gap-1 px-1 py-2 text-[0.7rem] font-medium leading-tight text-blue-700'
    : 'flex min-w-0 flex-1 flex-col items-center justify-center gap-1 px-1 py-2 text-[0.7rem] font-medium leading-tight text-gray-500 transition hover:text-blue-700 focus:outline-none focus:text-blue-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
