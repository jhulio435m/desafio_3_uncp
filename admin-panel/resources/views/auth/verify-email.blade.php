<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Antes de continuar, verifique su correo electrónico mediante el enlace que le enviamos. Si no recibió el mensaje, puede solicitar uno nuevo.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Se envió un nuevo enlace de verificación al correo electrónico registrado.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar correo de verificación
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uncp-green">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
