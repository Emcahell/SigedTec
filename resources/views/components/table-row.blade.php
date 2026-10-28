@props([
    'clickable' => false,
    'url' => null,
])

<tr
    {{ $attributes->merge(['class' => 'divide-y divide-gray-200']) }}
    @if ($clickable && $url)
        x-data="{ open: false }"
        @click="window.location.href = '{{ $url }}'"
        style="cursor: pointer;"
    @endif
>
    {{ $slot }}
</tr>
