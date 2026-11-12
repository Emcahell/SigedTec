<x-guest-layout>
    <div class="mb-4 flex items-start gap-2 text-sm text-gray-600">
        <x-icon-envelope class="text-blue-600" />
        <p>
            {{ __('Gracias por registrarse. Antes de comenzar, verifique su correo electrónico haciendo clic en el enlace que le enviamos. Si no lo recibió, podemos enviarle uno nuevo.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Se envió un nuevo enlace de verificación al correo electrónico proporcionado.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="inline-flex items-center gap-1.5">
                    <x-icon-envelope />
                    {{ __('Reenviar correo de verificación') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="inline-flex items-center gap-1.5 underline text-sm text-red-600 hover:text-red-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <x-icon-logout class="text-red-600" />
                {{ __('Cerrar sesión') }}
            </button>
        </form>
    </div>
</x-guest-layout>
