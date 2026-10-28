@props([
    'sortable' => false,
    'sortField' => null,
    'currentSort' => null,
    'sortDirection' => 'asc',
])

@php
$classes = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';

if ($sortable && $sortField === $currentSort) {
    $classes .= ' text-indigo-600';
}
@endphp

<th
    {{ $attributes->merge(['class' => $classes]) }}
    @if ($sortable)
        x-data="{ sort: '{{ $sortDirection }}' }"
        @click="window.dispatchEvent(new CustomEvent('sort-table', { detail: '{{ $sortField }}' }))"
        style="cursor: pointer;"
    @endif
>
    {{ $slot }}

    @if ($sortable && $sortField === $currentSort)
        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    @endif
</th>
