<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900">Canales oficiales</h2>
                <p class="mt-0.5 text-sm text-gray-500">Rutas válidas para iniciar o formalizar el proceso de proyección social.</p>
            </div>
            <button
                @click="$dispatch('open-modal', 'create-link-modal')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:shadow"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo canal
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @include('bot.partials.status')

            {{-- Search & Filters --}}
            <form method="GET" action="{{ route('bot.links') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por título, URL o palabras clave…" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green">
                    </div>
                    <select name="category" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($selectedCategory ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-gray-400">{{ $links->total() }} resultado(s)</p>
                    <div class="flex gap-2">
                        @if(($search ?? '') || ($selectedCategory ?? ''))
                            <a href="{{ route('bot.links') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Limpiar</a>
                        @endif
                        <button type="submit" class="text-xs font-semibold text-white bg-uncp-green hover:bg-uncp-green-light px-4 py-1.5 rounded-lg transition shadow-sm">Buscar</button>
                    </div>
                </div>
            </form>

            {{-- Links List --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Canales y enlaces registrados ({{ $links->total() }})</h3>
                </div>

                @forelse ($links as $link)
                    <details class="group bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md hover:border-gray-300">
                        <summary class="flex items-center justify-between p-4 cursor-pointer select-none hover:bg-gray-50/80 list-none">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <span class="h-2 w-2 rounded-full flex-shrink-0 {{ $link->is_active ? 'bg-uncp-green-logo shadow-[0_0_6px_rgba(16,185,129,0.6)]' : 'bg-gray-300' }}"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="text-sm font-semibold text-gray-900 truncate block">{{ $link->title }}</span>
                                    <span class="text-xs text-gray-400 mt-0.5 block truncate">
                                        <span class="text-uncp-green">{{ $link->url }}</span>
                                        @if($link->category) · <strong class="text-gray-500 font-medium">{{ $link->category->name }}</strong> @endif
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                                <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $link->is_active ? 'bg-uncp-bg border border-uncp-gold/50 text-uncp-green' : 'bg-gray-100 border border-gray-200 text-gray-500' }}">
                                    {{ $link->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                                <svg class="h-4 w-4 text-gray-400 transform transition-transform duration-200 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="px-5 pb-5 pt-4 border-t border-gray-100 bg-gray-50/40">
                            <form method="POST" action="{{ route('bot.links.update', $link) }}">
                                @csrf
                                @method('PATCH')
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Título del Canal</label>
                                        <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="title" value="{{ $link->title }}" required>
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">URL</label>
                                        <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="url" value="{{ $link->url }}" required>
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Categoría</label>
                                        <select class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="knowledge_category_id">
                                            <option value="">Sin categoría</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected($link->knowledge_category_id === $category->id)>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex flex-col space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Palabras clave</label>
                                        <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="keywords" value="{{ $link->keywords }}" placeholder="mesa partes, solicitud">
                                    </div>
                                    <div class="flex flex-col space-y-1 md:col-span-2">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Descripción para el ciudadano</label>
                                        <textarea class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="description" rows="2">{{ $link->description }}</textarea>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input class="rounded border-gray-300 text-uncp-green focus:ring-uncp-green h-4 w-4" name="is_active" type="checkbox" value="1" id="is_active_{{ $link->id }}" @checked($link->is_active)>
                                        <label for="is_active_{{ $link->id }}" class="text-sm text-gray-600">Activo en el Bot</label>
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2 justify-end">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-uncp-green-logo hover:bg-uncp-green text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Guardar
                                    </button>
                                    <button class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-rose-200 bg-white text-sm font-medium text-rose-700 hover:bg-rose-50 transition-all duration-200" form="delete-link-{{ $link->id }}" type="submit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Eliminar
                                    </button>
                                </div>
                            </form>
                            <form id="delete-link-{{ $link->id }}" method="POST" action="{{ route('bot.links.destroy', $link) }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </details>
                @empty
                    <div class="bg-white rounded-xl border border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <p class="text-sm text-gray-400 font-medium">No hay canales registrados.</p>
                        <button @click="$dispatch('open-modal', 'create-link-modal')" class="mt-3 text-sm font-semibold text-uncp-green hover:text-uncp-green-light transition">Agregar el primer canal →</button>
                    </div>
                @endforelse
            </div>

            {{ $links->links() }}
        </div>
    </div>

    {{-- CREATE LINK MODAL --}}
    <x-modal name="create-link-modal" focusable>
        <div class="p-0 overflow-hidden">
            <div class="bg-white border-b border-gray-200 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Nuevo canal / enlace oficial</h3>
                    <p class="text-xs text-gray-500 mt-0.5">El enlace estará disponible de inmediato para el bot.</p>
                </div>
                <button @click="$dispatch('close-modal', 'create-link-modal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('bot.links.store') }}">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="flex flex-col space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Título del Canal *</label>
                            <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="title" placeholder="Ej. ADESA UNCP" required>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">URL *</label>
                            <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="url" placeholder="https://…" required>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Categoría</label>
                            <select class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="knowledge_category_id">
                                <option value="">Sin categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Palabras clave</label>
                            <input class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="keywords" placeholder="mesa partes, solicitud, tramite">
                        </div>
                        <div class="flex flex-col space-y-1 md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Descripción para el ciudadano</label>
                            <textarea class="rounded-lg border-gray-200 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="description" rows="2" placeholder="Describe brevemente qué se realiza en este enlace..."></textarea>
                        </div>
                        <div class="flex items-center gap-2 md:col-span-2">
                            <input class="rounded border-gray-300 text-uncp-green focus:ring-uncp-green h-4 w-4" name="is_active" type="checkbox" value="1" checked id="create_link_is_active">
                            <label for="create_link_is_active" class="text-sm text-gray-600">Activar en el bot inmediatamente</label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                    <button type="button" @click="$dispatch('close-modal', 'create-link-modal')" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 border border-gray-200 rounded-lg hover:bg-gray-100 transition">Cancelar</button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Crear canal
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
