<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Configuración del orientador</h2>
            <p class="mt-1 text-sm text-gray-500">Mensajes base, alcance del canal, horario de atención y categorías del proceso.</p>
        </div>
    </x-slot>

    <style>
        summary::-webkit-details-marker {
            display: none;
        }
    </style>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('bot.partials.status')

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Left Column: Bot Settings Accordion -->
                <div class="space-y-4">
                    <h3 class="text-md font-bold text-gray-600 px-1">Mensajes y Respuestas Automáticas</h3>
                    
                    @forelse ($settings as $setting)
                        <details class="group bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow">
                            <summary class="flex items-center justify-between p-4 cursor-pointer select-none hover:bg-gray-50 list-none">
                                <div class="flex items-center gap-3">
                                    <svg class="h-5 w-5 text-uncp-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-800">{{ $setting->label }}</span>
                                </div>
                                <span class="transition group-open:rotate-180">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="px-5 pb-5 pt-3 border-t border-gray-100 bg-gray-50/50">
                                <form method="POST" action="{{ route('bot.settings.update', $setting) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex flex-col space-y-2">
                                        <textarea class="w-full rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="value" rows="{{ $setting->key === 'system_prompt' ? '16' : '4' }}" required>{{ $setting->value }}</textarea>
                                        @if ($setting->description)
                                            <p class="text-xs text-gray-400 italic">{{ $setting->description }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                                            Guardar Configuración
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </details>
                    @empty
                        <div class="bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-500">
                            No hay variables de configuración registradas.
                        </div>
                    @endforelse
                </div>

                <!-- Right Column: Category Manager -->
                <div class="space-y-4">
                    <h3 class="text-md font-bold text-gray-600 px-1">Gestión de Categorías</h3>

                    <!-- Collapsible Create Category Card -->
                    <details class="group bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow">
                        <summary class="flex items-center justify-between p-4 cursor-pointer select-none hover:bg-gray-50 list-none font-semibold">
                            <span class="text-gray-800 flex items-center gap-2">
                                <svg class="h-5 w-5 text-uncp-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Nueva Categoría
                            </span>
                            <span class="transition group-open:rotate-180">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </summary>
                        <div class="px-5 pb-5 pt-3 border-t border-gray-100 bg-gray-50/20">
                            <form method="POST" action="{{ route('bot.categories.store') }}">
                                @csrf
                                <div class="space-y-4">
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Nombre</label>
                                        <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="name" placeholder="Ej. Requisitos, Zootecnia" required>
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase">Descripción</label>
                                        <textarea class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="description" rows="3" placeholder="Describe brevemente esta categoría..."></textarea>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="rounded border-gray-300 text-uncp-green focus:ring-uncp-green h-4 w-4" name="is_active" type="checkbox" value="1" checked id="cat_is_active">
                                        <label for="cat_is_active" class="text-sm font-semibold text-gray-700">Categoría activa</label>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end">
                                    <x-primary-button class="bg-uncp-gold-web hover:bg-uncp-gold-dark text-black">Guardar categoría</x-primary-button>
                                </div>
                            </form>
                        </div>
                    </details>

                    <!-- Categories list -->
                    <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider border-b border-gray-100 pb-3">Categorías actuales</h4>
                        <div class="divide-y divide-gray-100">
                            @foreach ($categories as $category)
                                <div class="py-3 flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $category->description ?: 'Sin descripción' }}</p>
                                    </div>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $category->is_active ? 'bg-uncp-bg border border-uncp-gold/50 text-uncp-green' : 'bg-gray-100 border border-gray-200 text-gray-600' }}">
                                        {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
