<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            {{ __('Nueva solicitud') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <form method="POST" action="{{ route('requests.store') }}" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label for="office" class="block text-sm font-medium text-blue-900">
                            {{ __('Oficina solicitante') }}
                        </label>
                        <input id="office" name="office" type="text" value="{{ old('office') }}"
                               placeholder="Ej. Oficina de Tesorería"
                               class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                               required>
                        <x-input-error :messages="$errors->get('office')" class="mt-2" />
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-blue-900">
                            {{ __('Categoría de equipo') }}
                        </label>
                        <select id="category_id" name="category_id"
                                class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">{{ __('Seleccione una categoría') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                                    {{ $category->nombre }} — {{ $category->disponibles_count }} {{ __('disponible(s)') }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-blue-900">
                            {{ __('Cantidad de equipos') }}
                        </label>
                        <input id="quantity" name="quantity" type="number" min="1" max="50"
                               value="{{ old('quantity', 1) }}"
                               class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                               required>
                        <p class="mt-1 text-xs text-blue-600">
                            {{ __('Entre 1 y 50 equipos por solicitud. La cantidad disponible es informativa y se confirma al aprobar.') }}
                        </p>
                        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-2 border-t border-blue-100">
                        <a href="{{ route('requests.mine') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            {{ __('Registrar solicitud') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
