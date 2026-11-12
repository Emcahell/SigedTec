<x-guest-layout>
    <div class="mb-6 border-b border-gray-200 pb-4 text-center">
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Acceso al sistema') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Ingrese con sus credenciales institucionales') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-blue-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Ingresar') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 border-t border-gray-200 pt-4 text-center text-xs text-gray-500">
        <p>
            {{ __('Las cuentas de solicitante son creadas por Soporte Técnico.') }}
        </p>
    </div>
</x-guest-layout>
