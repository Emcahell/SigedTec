@props(['sortable' => false])

<tr {{ $attributes->merge(['class' => 'text-xs uppercase text-gray-500 divide-y divide-gray-200']) }}>
    {{ $slot }}
</tr>
