<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-900 leading-tight">
            {{ __('Registrar solicitante') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-blue-100 sm:rounded-lg">
                <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-blue-900">
                            {{ __('Nombre completo') }}
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}"
                               class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                               required autofocus autocomplete="name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-blue-900">
                            {{ __('Correo electrónico') }}
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                               required autocomplete="username">
                        <p class="mt-1 text-xs text-blue-600">
                            {{ __('Con este correo el solicitante iniciará sesión.') }}
                        </p>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-medium text-blue-900">
                                {{ __('Contraseña') }}
                            </label>
                            <input id="password" name="password" type="password"
                                   class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                   required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-blue-900">
                                {{ __('Confirmar contraseña') }}
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="block mt-1 w-full rounded-md border-blue-200 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500"
                                   required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-2 border-t border-blue-100">
                        <a href="{{ route('users.index') }}" class="text-sm text-blue-600 hover:text-blue-800 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-700 border border-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            {{ __('Registrar usuario') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
