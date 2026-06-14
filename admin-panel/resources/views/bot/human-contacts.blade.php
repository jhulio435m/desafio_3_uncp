<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold leading-tight text-gray-900">Pedidos de orientación humana</h2>
            <p class="mt-0.5 text-sm text-gray-500">Representantes que necesitan llamada o mensaje para entender el proceso de proyección social.</p>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        openEditModal: false,
        openDeleteModal: false,
        deleteUrl: '',
        selectedRequest: {
            id: '',
            citizen_name: '',
            phone: '',
            topic: '',
            message: '',
            status: '',
            internal_notes: '',
            contacted_at: '',
            user_name: '',
            has_related: false,
            related_ticket: '',
            create_route: '',
            show_route: '',
            update_url: ''
        },
        filterTimer: null,
        loading: false,
        init() {
            this.bindPagination();
        },
        submitFilters() {
            clearTimeout(this.filterTimer);
            this.filterTimer = setTimeout(() => this.fetchResults(), 300);
        },
        fetchResults(url = null) {
            const form = this.$refs.filters;
            const targetUrl = url ? new URL(url, window.location.origin) : new URL(form.action, window.location.origin);

            if (!url) {
                const params = new URLSearchParams(new FormData(form));
                [...params.entries()].forEach(([key, value]) => {
                    if (value === '') params.delete(key);
                });
                targetUrl.search = params.toString();
            }

            this.loading = true;

            fetch(targetUrl.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then((response) => response.text())
                .then((html) => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    document.getElementById('human-result-count').innerHTML = doc.getElementById('human-result-count').innerHTML;
                    document.getElementById('human-list-title').innerHTML = doc.getElementById('human-list-title').innerHTML;
                    document.getElementById('human-results').innerHTML = doc.getElementById('human-results').innerHTML;
                    document.getElementById('human-pagination').innerHTML = doc.getElementById('human-pagination').innerHTML;
                    window.Alpine?.initTree(document.getElementById('human-results'));
                    window.history.replaceState({}, '', targetUrl.toString());
                    this.$nextTick(() => {
                        this.bindPagination();
                        window.lucide?.createIcons();
                    });
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        bindPagination() {
            this.$nextTick(() => {
                document.querySelectorAll('#human-pagination a').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        this.fetchResults(link.href);
                    }, { once: true });
                });
            });
        },
        clearFilters() {
            this.$refs.filters.querySelectorAll('input, select').forEach((field) => {
                field.value = '';
            });
            this.fetchResults(this.$refs.filters.action);
        }
    }">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('bot.partials.status')

            {{-- Search & Filters --}}
            <form method="GET" action="{{ route('bot.human-contacts') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4" x-ref="filters" @submit.prevent="fetchResults()">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre, teléfono, tema o mensaje..." class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green" @input="submitFilters()">
                    </div>
                    <select name="status" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3" @change="fetchResults()">
                        <option value="">Todos los estados</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ ($selectedStatus ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-gray-400">
                        <span id="human-result-count">{{ $requests->total() }} resultado(s)</span>
                        <span x-show="loading" class="ml-2 text-uncp-green" style="display: none;">Actualizando...</span>
                    </p>
                    <div>
                        <button type="button" @click="clearFilters()" class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Limpiar</button>
                    </div>
                </div>
            </form>

            {{-- List --}}
            <div class="space-y-3">
                <h3 id="human-list-title" class="text-sm font-bold text-gray-500 uppercase tracking-wider px-1">Pedidos de contacto registrados ({{ $requests->total() }})</h3>

                <div id="human-results" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="grid grid-cols-[minmax(0,1fr)_150px_112px] gap-4 border-b border-gray-200 bg-gray-50 px-4 py-2 text-[11px] font-semibold uppercase text-gray-500 max-lg:hidden">
                        <span>Pedido</span>
                        <span>Estado</span>
                        <span class="text-right">Acción</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                @forelse ($requests as $request)
                    @php
                        $dotClasses = match($request->status) {
                            'Pendiente'          => 'bg-amber-500',
                            'En Contacto'        => 'bg-uncp-blue',
                            'Derivado a Trámite' => 'bg-uncp-gold-web',
                            'Resuelto'           => 'bg-uncp-green-logo',
                            'Inubicable'         => 'bg-rose-500',
                            default              => 'bg-gray-400',
                        };
                        $payload = [
                            'id' => $request->id,
                            'citizen_name' => $request->citizen_name ?: 'Ciudadano sin nombre',
                            'phone' => $request->phone,
                            'topic' => $request->topic ?: 'Consulta general',
                            'message' => $request->message,
                            'status' => $request->status,
                            'internal_notes' => $request->internal_notes,
                            'contacted_at' => $request->contacted_at ? $request->contacted_at->format('d/m/Y H:i') : 'Sin gestionar aún',
                            'user_name' => $request->user ? $request->user->name : 'Sin asignar',
                            'has_related' => (bool) $request->related_request,
                            'related_ticket' => $request->related_request ? $request->related_request->ticket_id : '',
                            'create_route' => route('requests.create', ['representative_name' => $request->citizen_name, 'description' => $request->message]),
                            'show_route' => $request->related_request ? route('requests.show', $request->related_request->id) : '',
                            'update_url' => route('bot.human-contacts.update', $request),
                        ];
                    @endphp

                    <article class="grid gap-3 px-4 py-3 transition hover:bg-gray-50 lg:grid-cols-[minmax(0,1fr)_150px_112px] lg:items-center lg:gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-semibold text-gray-500">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                <span class="text-[11px] text-gray-400">{{ $request->preferred_channel }}</span>
                            </div>
                            <h4 class="mt-1 truncate text-sm font-semibold text-gray-900">
                                {{ $request->citizen_name ?: 'Ciudadano sin nombre' }}
                            </h4>
                            <p class="truncate text-xs text-gray-500">{{ $request->phone }} · {{ $request->topic ?: 'Consulta general' }}</p>
                            <p class="mt-1 line-clamp-1 text-sm text-gray-600">{{ $request->message ?: 'Sin mensaje registrado.' }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <span class="h-2 w-2 flex-shrink-0 rounded-full {{ $dotClasses }}"></span>
                            {{ $request->status }}
                        </div>
                        <div class="flex justify-start gap-2 lg:justify-end">
                                <button type="button"
                                        @click="selectedRequest = @js($payload); openEditModal = true"
                                        class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-md transition-colors border border-gray-300 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Gestionar
                                </button>
                                <button type="button"
                                        @click="deleteUrl = '{{ route('bot.human-contacts.destroy', $request) }}'; openDeleteModal = true;"
                                        class="inline-flex items-center justify-center p-1.5 bg-white hover:bg-rose-50 text-gray-400 hover:text-rose-600 text-xs rounded-md transition-colors border border-gray-300 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                        </div>
                    </article>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        No hay pedidos de contacto registrados.
                    </div>
                @endforelse
                    </div>
                </div>
            </div>

            <div id="human-pagination">
                {{ $requests->links() }}
            </div>
        </div>

        {{-- VIEW / EDIT MODAL --}}
        <div x-show="openEditModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full flex flex-col overflow-hidden" @click.away="openEditModal = false" x-show="openEditModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900" x-text="'Ver pedido: ' + selectedRequest.citizen_name"></h3>
                        <p class="text-xs text-gray-500 mt-0.5" x-text="'Tel: ' + selectedRequest.phone + ' · Tema: ' + selectedRequest.topic"></p>
                    </div>
                    <button type="button" @click="openEditModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="selectedRequest.update_url" method="POST" class="flex flex-col flex-1">
                    @csrf
                    @method('PATCH')

                    <div class="p-6 space-y-4 overflow-y-auto max-h-[60vh]">
                        <div class="flex flex-col space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Mensaje del Ciudadano</label>
                            <div class="bg-uncp-bg border border-uncp-gold/40 rounded-xl p-4 text-sm text-gray-700 italic relative">
                                <span class="absolute top-2 left-2 text-uncp-gold/60 text-3xl font-serif leading-none">"</span>
                                <p class="pl-4 pr-2" x-text="selectedRequest.message"></p>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Estado de la atención</label>
                                <select class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm w-full" name="status" x-model="selectedRequest.status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Auditabilidad del trámite</label>
                                <div class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-xs text-gray-600 space-y-1">
                                    <p><strong>Última gestión:</strong> <span x-text="selectedRequest.contacted_at"></span></p>
                                    <p><strong>Responsable:</strong> <span x-text="selectedRequest.user_name"></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Notas internas / Bitácora de seguimiento</label>
                            <textarea class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="internal_notes" rows="3" placeholder="Registrar detalles de llamadas realizadas o acuerdos…" x-model="selectedRequest.internal_notes"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <template x-if="selectedRequest.has_related">
                                <a :href="selectedRequest.show_route" class="inline-flex items-center text-xs font-semibold text-uncp-green hover:text-uncp-green-light transition-colors">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Ver Solicitud Formal (Ticket: <span x-text="selectedRequest.related_ticket"></span>)
                                </a>
                            </template>
                            <template x-if="!selectedRequest.has_related">
                                <a :href="selectedRequest.create_route" class="inline-flex items-center text-xs font-semibold text-uncp-green hover:text-uncp-green-light transition-colors">
                                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    Crear Solicitud Formal
                                </a>
                            </template>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" @click="openEditModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors text-sm">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black font-semibold rounded-lg shadow-sm transition-all text-sm">Guardar Cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE CONFIRM MODAL --}}
        <div x-show="openDeleteModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" @click.away="openDeleteModal = false" x-show="openDeleteModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-full">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">¿Eliminar pedido?</h3>
                </div>
                <p class="text-gray-500 text-sm mb-6">Esta acción es permanente. El registro del pedido de contacto humano se eliminará de la base de datos.</p>
                <div class="flex space-x-3">
                    <button type="button" @click="openDeleteModal = false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancelar</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-colors shadow-lg shadow-rose-200">Sí, eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
