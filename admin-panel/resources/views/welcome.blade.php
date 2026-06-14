<x-guest-layout>
    <div class="text-center">
        <h1 class="text-xl font-semibold text-gray-900">Orientador de Proyección Social</h1>
        <p class="mt-2 text-sm text-gray-600">
            Panel administrativo para la guía, canales oficiales y contactos de orientación.
        </p>

        <div class="mt-6">
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-uncp-green px-4 py-2 text-sm font-semibold text-white hover:bg-uncp-green/90">
                    Ir al panel
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center rounded-md bg-uncp-green px-4 py-2 text-sm font-semibold text-white hover:bg-uncp-green/90">
                    Ingresar
                </a>
            @endauth
        </div>
    </div>
</x-guest-layout>
