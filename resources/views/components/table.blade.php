@props([
    'striped' => true,
    'hover' => true,
    'bordered' => true,
    'responsive' => true,
    'emptyMessage' => null,
])

@php
$classes = 'min-w-full divide-y ';

if ($striped) {
    $classes .= 'bg-white ';
}

if ($bordered) {
    $classes .= 'divide-gray-200 ';
}

if ($hover) {
    $classes .= 'hover:bg-gray-50 ';
}
@endphp

@if ($responsive)
    <div class="overflow-x-auto">
@endif

<table {{ $attributes->merge(['class' => $classes]) }}>
    @if ($header)
        <thead class="bg-gray-50">
            {{ $header }}
        </thead>
    @endif

    <tbody class="bg-white divide-y divide-gray-200">
        {{ $slot }}
    </tbody>
</table>

@if (! empty($emptyMessage) && $slot === '')
    <tbody>
        <tr>
            <td class="px-6 py-4 text-center text-gray-500">{{ $emptyMessage }}</td>
        </tr>
    </tbody>
@endif

@if ($responsive)
    </div>
@endif
