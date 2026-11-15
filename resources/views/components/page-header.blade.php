@props([])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="min-w-0">
        {{ $title ?? $slot }}
    </div>

    @isset($actions)
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end w-full sm:w-auto shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
