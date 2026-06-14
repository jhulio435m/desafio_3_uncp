<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">Solicitudes de Proyección Social</h2>
                <p class="mt-0.5 text-sm text-gray-500">Registro y seguimiento de solicitudes formales de las comunidades.</p>
            </div>
            <button
                type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-create-request'))"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-uncp-gold-web border border-transparent rounded-lg font-semibold text-sm text-black hover:bg-uncp-gold-dark transition shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nueva Solicitud
            </button>
        </div>
    </x-slot>

    @php
        $requestStatuses = ['Recibido','Evaluando','Asignado','En Ejecución','Finalizado'];
        $institutionTypes = [
            'Comunidad Campesina',
            'Comunidad Urbana',
            'Municipalidad / Gob. Local',
            'Institución Educativa',
            'Organización Social',
            'Otro',
        ];
        $statusDot = fn ($requestStatus) => match($requestStatus) {
            'Finalizado'   => 'bg-uncp-green-logo',
            'En Ejecución' => 'bg-uncp-blue',
            'Asignado'     => 'bg-uncp-gold-web',
            'Evaluando'    => 'bg-amber-500',
            default        => 'bg-gray-400',
        };
    @endphp

    <div
        class="py-8"
        x-data="{
            openCreateModal: {{ $errors->any() ? 'true' : 'false' }},
            openDetailModal: false,
            selectedRequest: {},
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
                        document.getElementById('requests-result-count').innerHTML = doc.getElementById('requests-result-count').innerHTML;
                        document.getElementById('requests-results').innerHTML = doc.getElementById('requests-results').innerHTML;
                        document.getElementById('requests-pagination').innerHTML = doc.getElementById('requests-pagination').innerHTML;
                        window.Alpine?.initTree(document.getElementById('requests-results'));
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
                    document.querySelectorAll('#requests-pagination a').forEach((link) => {
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
        }"
        @open-create-request.window="openCreateModal = true"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('status'))
                <div class="p-4 rounded-xl bg-uncp-bg border border-uncp-gold/50 text-sm text-uncp-green font-medium">{{ session('status') }}</div>
            @endif

            <form method="GET" action="{{ route('requests.index') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4" x-ref="filters" @submit.prevent="fetchResults()">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
                    <div class="relative sm:col-span-2">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input
                            type="search"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Buscar por ticket, institución, representante o ubicación..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green"
                            @input="submitFilters()"
                        >
                    </div>
                    <select name="status" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3" @change="fetchResults()">
                        <option value="">Todos los estados</option>
                        @foreach($requestStatuses as $s)
                            <option value="{{ $s }}" {{ ($status ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="from" value="{{ $from ?? '' }}" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3" @change="fetchResults()">
                    <input type="date" name="to" value="{{ $to ?? '' }}" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3" @change="fetchResults()">
                </div>
                <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-gray-400">
                        <span id="requests-result-count">{{ $requests->total() }} resultado(s) encontrado(s)</span>
                        <span x-show="loading" class="ml-2 text-uncp-green" style="display: none;">Actualizando...</span>
                    </p>
                    <button type="button" @click="clearFilters()" class="inline-flex justify-center text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Limpiar filtros</button>
                </div>
            </form>

            <div id="requests-results" class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="grid grid-cols-[minmax(0,1fr)_140px_120px] gap-4 border-b border-gray-200 bg-gray-50 px-4 py-2 text-[11px] font-semibold uppercase text-gray-500 max-lg:hidden">
                    <span>Solicitud</span>
                    <span>Estado</span>
                    <span class="text-right">Acción</span>
                </div>
                <div class="divide-y divide-gray-100">
                @forelse ($requests as $request)
                    @php
                        $payload = [
                            'ticket_id' => $request->ticket_id,
                            'institution_name' => $request->institution_name,
                            'institution_type' => $request->institution_type,
                            'representative_name' => $request->representative_name,
                            'representative_dni' => $request->representative_dni,
                            'location' => $request->location,
                            'description' => $request->description,
                            'status' => $request->status,
                            'created_at' => $request->created_at->format('d/m/Y H:i'),
                            'updated_at' => $request->updated_at->format('d/m/Y H:i'),
                            'update_url' => route('requests.update', $request),
                        ];
                    @endphp
                    <article class="grid gap-3 px-4 py-3 transition hover:bg-gray-50 lg:grid-cols-[minmax(0,1fr)_140px_120px] lg:items-center lg:gap-4">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded border border-gray-300 bg-gray-50 px-1.5 py-0.5 text-[11px] font-semibold text-gray-700">{{ $request->ticket_id }}</span>
                                <span class="text-xs text-gray-500">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <h3 class="mt-1 truncate text-sm font-semibold text-gray-900">{{ $request->institution_name }}</h3>
                            <p class="truncate text-xs text-gray-500">{{ $request->institution_type }} · {{ $request->location }} · {{ $request->representative_name }}</p>
                            <p class="mt-1 line-clamp-1 text-sm text-gray-600">{{ $request->description }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <span class="h-2 w-2 rounded-full {{ $statusDot($request->status) }}"></span>
                            {{ $request->status }}
                        </div>
                        <div class="flex justify-start lg:justify-end">
                                <button
                                    type="button"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                                    @click="selectedRequest = @js($payload); openDetailModal = true"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Gestionar
                                </button>
                        </div>
                    </article>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-sm font-medium text-gray-500">No hay solicitudes que coincidan con los filtros.</p>
                        <button type="button" @click="openCreateModal = true" class="mt-3 text-sm font-semibold text-uncp-green hover:text-uncp-green-light">Registrar solicitud</button>
                    </div>
                @endforelse
                </div>
            </div>

            <div id="requests-pagination">
                {{ $requests->links() }}
            </div>
        </div>

        <div x-show="openDetailModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full overflow-hidden" @click.away="openDetailModal = false" x-show="openDetailModal" x-transition>
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-md bg-uncp-green px-2 py-1 text-xs font-bold text-white" x-text="selectedRequest.ticket_id"></span>
                            <span class="text-xs font-semibold text-gray-500" x-text="selectedRequest.created_at"></span>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-gray-900" x-text="selectedRequest.institution_name"></h3>
                        <p class="text-xs text-gray-500" x-text="selectedRequest.institution_type + ' · ' + selectedRequest.location"></p>
                    </div>
                    <button type="button" @click="openDetailModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="selectedRequest.update_url" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="max-h-[70vh] overflow-y-auto p-6 space-y-5">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="rounded-lg border border-gray-200 p-4">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Representante</p>
                                <p class="mt-1 text-sm font-bold text-gray-900" x-text="selectedRequest.representative_name"></p>
                                <p class="text-xs text-gray-500">DNI: <span x-text="selectedRequest.representative_dni"></span></p>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Estado de atención</label>
                                <select name="status" x-model="selectedRequest.status" class="mt-2 w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-uncp-green focus:ring-uncp-green">
                                    @foreach($requestStatuses as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="rounded-xl border border-uncp-gold/40 bg-uncp-bg p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-uncp-green">Necesidad reportada</p>
                            <p class="mt-2 whitespace-pre-line text-sm text-gray-700" x-text="selectedRequest.description"></p>
                        </div>
                        <div class="grid gap-3 text-xs text-gray-500 sm:grid-cols-2">
                            <p><strong class="text-gray-700">Creado:</strong> <span x-text="selectedRequest.created_at"></span></p>
                            <p><strong class="text-gray-700">Última actualización:</strong> <span x-text="selectedRequest.updated_at"></span></p>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="button" @click="openDetailModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors text-sm">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black font-semibold rounded-lg shadow-sm transition-all text-sm">Guardar estado</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openCreateModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full overflow-hidden" @click.away="openCreateModal = false" x-show="openCreateModal" x-transition>
                <div class="border-b border-gray-100 bg-gray-50 px-6 py-4 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Nueva solicitud formal</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Registra el requerimiento y deja el ticket listo para seguimiento.</p>
                    </div>
                    <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('requests.store') }}">
                    @csrf
                    <div class="max-h-[70vh] overflow-y-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="representative_name" value="Nombre del representante" />
                                <x-text-input id="representative_name" class="block mt-1 w-full" type="text" name="representative_name" :value="old('representative_name')" required />
                                <x-input-error :messages="$errors->get('representative_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="representative_dni" value="DNI del representante" />
                                <x-text-input id="representative_dni" class="block mt-1 w-full" type="text" name="representative_dni" :value="old('representative_dni')" required />
                                <x-input-error :messages="$errors->get('representative_dni')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="institution_name" value="Institución o comunidad" />
                                <x-text-input id="institution_name" class="block mt-1 w-full" type="text" name="institution_name" :value="old('institution_name')" required />
                                <x-input-error :messages="$errors->get('institution_name')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="institution_type" value="Tipo de institución" />
                                <select id="institution_type" name="institution_type" class="border-gray-300 focus:border-uncp-green focus:ring-uncp-green rounded-lg shadow-sm mt-1 block w-full text-sm">
                                    @foreach($institutionTypes as $type)
                                        <option value="{{ $type }}" {{ old('institution_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('institution_type')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="location" value="Ubicación" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="description" value="Descripción de la necesidad" />
                                <textarea id="description" name="description" rows="5" class="border-gray-300 focus:border-uncp-green focus:ring-uncp-green rounded-lg shadow-sm mt-1 block w-full text-sm" required>{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="status" value="Estado inicial" />
                                <select id="status" name="status" class="border-gray-300 focus:border-uncp-green focus:ring-uncp-green rounded-lg shadow-sm mt-1 block w-full text-sm">
                                    @foreach($requestStatuses as $s)
                                        <option value="{{ $s }}" {{ old('status', 'Recibido') === $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors text-sm">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black font-semibold rounded-lg shadow-sm transition-all text-sm">Guardar solicitud</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
