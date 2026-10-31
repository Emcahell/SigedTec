<?php

$equipment = $equipment ?? null;
$categories = $categories ?? collect();
$estados = $estados ?? [];
?>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <x-input-label for="codigo_patrimonial" :value="__('Código patrimonial')" />
        <x-text-input id="codigo_patrimonial" class="block mt-1 w-full" type="text" name="codigo_patrimonial" :value="old('codigo_patrimonial', $equipment?->codigo_patrimonial)" placeholder="BI-2026-0001" required />
        <x-input-error :messages="$errors->get('codigo_patrimonial')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" :value="__('Categoría')" />
        <select id="category_id" name="category_id"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                required>
            <option value="">{{ __('Seleccione una categoría') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $equipment?->category_id) === (string) $category->id)>
                    {{ $category->nombre }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="marca" :value="__('Marca')" />
        <x-text-input id="marca" class="block mt-1 w-full" type="text" name="marca" :value="old('marca', $equipment?->marca)" required />
        <x-input-error :messages="$errors->get('marca')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="modelo" :value="__('Modelo')" />
        <x-text-input id="modelo" class="block mt-1 w-full" type="text" name="modelo" :value="old('modelo', $equipment?->modelo)" required />
        <x-input-error :messages="$errors->get('modelo')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="cpu" :value="__('CPU')" />
        <x-text-input id="cpu" class="block mt-1 w-full" type="text" name="cpu" :value="old('cpu', $equipment?->cpu)" placeholder="Intel Core i5-1235U" />
        <x-input-error :messages="$errors->get('cpu')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="ram" :value="__('RAM')" />
        <x-text-input id="ram" class="block mt-1 w-full" type="text" name="ram" :value="old('ram', $equipment?->ram)" placeholder="16 GB" />
        <x-input-error :messages="$errors->get('ram')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="almacenamiento" :value="__('Almacenamiento')" />
        <x-text-input id="almacenamiento" class="block mt-1 w-full" type="text" name="almacenamiento" :value="old('almacenamiento', $equipment?->almacenamiento)" placeholder="512 GB SSD" />
        <x-input-error :messages="$errors->get('almacenamiento')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="estado" :value="__('Estado')" />
        <select id="estado" name="estado"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                required>
            <option value="">{{ __('Seleccione un estado') }}</option>
            @foreach ($estados as $valor => $etiqueta)
                <option value="{{ $valor }}" @selected(old('estado', $equipment?->estado?->value) === $valor)>
                    {{ $etiqueta }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('estado')" class="mt-2" />
    </div>
</div>

<div class="flex items-center justify-end gap-4 mt-6">
    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
       href="{{ route('equipments.index') }}">
        {{ __('Cancelar') }}
    </a>

    <x-primary-button>
        {{ $submitLabel ?? 'Guardar' }}
    </x-primary-button>
</div>
