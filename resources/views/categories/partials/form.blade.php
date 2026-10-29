<?php

$category = $category ?? null;
$submitLabel = $submitLabel ?? 'Guardar';
?>

<div>
    <x-input-label for="nombre" :value="__('Nombre')" />
    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre" :value="old('nombre', $category?->nombre)" required autofocus />
    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="descripcion" :value="__('Descripción')" />
    <textarea
        id="descripcion"
        name="descripcion"
        rows="3"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
        placeholder="{{ __('Ej. Equipos portátiles para trabajos de oficina y movilidad.') }}"
    >{{ old('descripcion', $category?->descripcion) }}</textarea>
    <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
</div>

<div class="flex items-center justify-end gap-4 mt-6">
    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
       href="{{ route('categories.index') }}">
        {{ __('Cancelar') }}
    </a>

    <x-primary-button>
        {{ $submitLabel }}
    </x-primary-button>
</div>
