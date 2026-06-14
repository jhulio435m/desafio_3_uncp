<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900">Panel principal</h2>
                <p class="mt-1 text-sm text-gray-500">Seguimiento compacto del canal de orientación comunitaria.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('requests.index') }}" class="inline-flex items-center justify-center rounded-md bg-uncp-gold-web px-3.5 py-2 text-sm font-semibold text-black shadow-sm transition hover:bg-uncp-gold-dark">
                    Nueva solicitud
                </a>
                <a href="{{ route('bot.human-contacts') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3.5 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Gestionar contacto
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $metricCards = [
            ['label' => 'Solicitudes', 'value' => $activeRequests, 'hint' => 'Registros formales'],
            ['label' => 'Pendientes', 'value' => $pendingHumanContacts, 'hint' => 'Por contactar'],
            ['label' => 'Guías activas', 'value' => $activeFaqs, 'hint' => 'Base de orientación'],
            ['label' => 'WhatsApp', 'value' => 'Live', 'hint' => 'Estado en tiempo real'],
        ];
        $statusDot = fn ($status) => match($status) {
            'Finalizado', 'Resuelto' => 'bg-uncp-green-logo',
            'En Ejecución', 'En Contacto' => 'bg-uncp-blue',
            'Asignado', 'Derivado a Trámite' => 'bg-uncp-gold-web',
            'Evaluando', 'Pendiente' => 'bg-amber-500',
            'Inubicable' => 'bg-rose-500',
            default => 'bg-gray-400',
        };
    @endphp

    <div class="lg:h-[calc(100vh-4rem)] lg:overflow-hidden" x-data="{ showRestartModal: false, showLogoutModal: false }">
        <div class="flex h-full w-full flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 2xl:px-10">
            @if (session('status'))
                <div class="rounded-md border border-uncp-gold/50 bg-white px-4 py-3 text-sm font-medium text-uncp-green shadow-sm">{{ session('status') }}</div>
            @endif

            @if ($errors->has('bot'))
                <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm">{{ $errors->first('bot') }}</div>
            @endif

            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($metricCards as $metric)
                    <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 shadow-sm">
                        <p class="text-[11px] font-semibold uppercase text-gray-500">{{ $metric['label'] }}</p>
                        <div class="mt-1 flex items-end justify-between gap-3">
                            <p class="text-2xl font-bold leading-none text-gray-900">{{ $metric['value'] }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $metric['hint'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid min-h-0 flex-1 grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_400px] 2xl:grid-cols-[minmax(0,1fr)_440px]">
                <section class="grid min-h-0 grid-cols-1 gap-4 lg:grid-rows-[auto_minmax(0,1fr)]">
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Flujo de solicitudes</h3>
                                <p class="mt-0.5 text-xs text-gray-500">Distribución por estado, limitada a los estados operativos.</p>
                            </div>
                            <a href="{{ route('requests.index') }}" class="text-xs font-semibold text-uncp-green hover:text-uncp-green-light">Ver panel</a>
                        </div>
                        <div class="grid gap-3 p-4 sm:grid-cols-5">
                            @foreach ($requestStatuses as $status)
                                @php
                                    $count = (int) ($requestStatusCounts[$status] ?? 0);
                                    $percent = min(100, $activeRequests > 0 ? round(($count / $activeRequests) * 100) : 0);
                                @endphp
                                <div class="rounded-md border border-gray-200 bg-gray-50/60 p-3">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full {{ $statusDot($status) }}"></span>
                                        <span class="truncate text-xs font-semibold text-gray-700">{{ $status }}</span>
                                    </div>
                                    <p class="mt-2 text-xl font-bold text-gray-900">{{ $count }}</p>
                                    <div class="mt-2 h-1.5 rounded-full bg-gray-200">
                                        <div class="h-1.5 rounded-full bg-uncp-green" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid min-h-0 grid-cols-1 gap-4 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
                        <div class="min-h-0 rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">Solicitudes recientes</h3>
                                <span class="text-xs text-gray-500">Últimas {{ $recentRequests->count() }}</span>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($recentRequests as $request)
                                    <div class="grid grid-cols-[minmax(0,1fr)_auto] gap-3 px-4 py-3">
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="rounded border border-gray-300 bg-gray-50 px-1.5 py-0.5 text-[11px] font-semibold text-gray-700">{{ $request->ticket_id }}</span>
                                                <span class="flex items-center gap-1.5 text-xs font-medium text-gray-600">
                                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot($request->status) }}"></span>
                                                    {{ $request->status }}
                                                </span>
                                            </div>
                                            <p class="mt-1 truncate text-sm font-semibold text-gray-900">{{ $request->institution_name }}</p>
                                            <p class="truncate text-xs text-gray-500">{{ $request->representative_name }} · {{ $request->location }}</p>
                                        </div>
                                        <p class="whitespace-nowrap text-xs text-gray-500">{{ $request->created_at->format('d/m H:i') }}</p>
                                    </div>
                                @empty
                                    <p class="p-5 text-center text-sm text-gray-500">No hay solicitudes registradas.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="min-h-0 rounded-lg border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">Pedidos por contactar</h3>
                                <a href="{{ route('bot.human-contacts') }}" class="text-xs font-semibold text-uncp-green hover:text-uncp-green-light">Gestionar</a>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($recentContacts as $request)
                                    <div class="px-4 py-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-gray-900">{{ $request->citizen_name ?: 'Ciudadano sin nombre' }}</p>
                                                <p class="truncate text-xs text-gray-500">{{ $request->topic ?: 'Consulta general' }} · {{ $request->phone }}</p>
                                            </div>
                                            <span class="flex shrink-0 items-center gap-1.5 text-xs font-medium text-gray-600">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot($request->status) }}"></span>
                                                {{ $request->status }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="p-5 text-center text-sm text-gray-500">No hay pedidos recientes.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="min-h-0 space-y-4">
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900">Canal WhatsApp</h3>
                                <div class="flex items-center gap-1.5 rounded-md border border-gray-200 bg-gray-50 px-2 py-1">
                                    <span id="wa-pulse" class="h-2 w-2 rounded-full bg-gray-400"></span>
                                    <span id="wa-status-text" class="text-[10px] font-semibold uppercase text-gray-600">Cargando</span>
                                </div>
                            </div>
                            <div class="mt-3 rounded-md border border-gray-200 bg-gray-50 px-3 py-2">
                                <span class="block text-[10px] font-semibold uppercase text-gray-500">Número</span>
                                <span id="wa-number" class="mt-1 block truncate text-sm font-semibold text-gray-900">-</span>
                                <span id="wa-detail" class="mt-1 block truncate text-xs text-gray-500">Conectando...</span>
                            </div>
                        </div>

                        <div class="p-4">
                            <div id="qr-container" class="relative flex min-h-[150px] w-full flex-col items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <div id="qr-loader" class="absolute inset-0 z-10 flex flex-col items-center justify-center rounded-lg bg-gray-50/90">
                                    <div class="h-8 w-8 animate-spin rounded-full border-2 border-uncp-green border-t-transparent"></div>
                                </div>
                                <img id="qr-img" class="hidden h-auto w-full max-w-[140px] rounded-md" alt="QR" />
                                <div id="connected-icon" class="hidden space-y-3 text-center">
                                    <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-uncp-bg text-uncp-green ring-4 ring-uncp-bg">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold uppercase text-uncp-green">Sistema activo</p>
                                </div>
                                <p id="qr-instructions" class="hidden mt-2 text-center text-[10px] font-medium text-gray-400">Configuración > Dispositivos vinculados</p>
                            </div>

                            <div class="mt-3 grid gap-2">
                                <button type="button" @click="showRestartModal = true" class="rounded-md border border-gray-300 bg-white py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">Reiniciar bot</button>
                                <div id="logout-form" class="hidden">
                                    <button type="button" @click="showLogoutModal = true" class="w-full rounded-md border border-rose-200 bg-rose-50 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">Desvincular</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                        <h3 class="text-sm font-semibold text-gray-900">Acciones rápidas</h3>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a href="{{ route('bot.faqs') }}" class="group flex min-h-20 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50/60 p-3 text-sm font-semibold text-gray-800 transition hover:border-uncp-gold hover:bg-white hover:shadow-sm" title="Guías de orientación">
                                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white text-uncp-green shadow-sm ring-1 ring-gray-200 group-hover:ring-uncp-gold/60">
                                    <i data-lucide="circle-help" class="h-4 w-4"></i>
                                </span>
                                <span>Guías</span>
                            </a>
                            <a href="{{ route('requests.index') }}" class="group flex min-h-20 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50/60 p-3 text-sm font-semibold text-gray-800 transition hover:border-uncp-gold hover:bg-white hover:shadow-sm" title="Solicitudes">
                                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white text-uncp-green shadow-sm ring-1 ring-gray-200 group-hover:ring-uncp-gold/60">
                                    <i data-lucide="clipboard-list" class="h-4 w-4"></i>
                                </span>
                                <span>Solicitudes</span>
                            </a>
                            <a href="{{ route('bot.human-contacts') }}" class="group flex min-h-20 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50/60 p-3 text-sm font-semibold text-gray-800 transition hover:border-uncp-gold hover:bg-white hover:shadow-sm" title="Contacto humano">
                                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white text-uncp-green shadow-sm ring-1 ring-gray-200 group-hover:ring-uncp-gold/60">
                                    <i data-lucide="headphones" class="h-4 w-4"></i>
                                </span>
                                <span>Contacto</span>
                            </a>
                            <a href="{{ route('bot.settings') }}" class="group flex min-h-20 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50/60 p-3 text-sm font-semibold text-gray-800 transition hover:border-uncp-gold hover:bg-white hover:shadow-sm" title="Ajustes">
                                <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white text-uncp-green shadow-sm ring-1 ring-gray-200 group-hover:ring-uncp-gold/60">
                                    <i data-lucide="settings" class="h-4 w-4"></i>
                                </span>
                                <span>Ajustes</span>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <div x-show="showRestartModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl" @click.away="showRestartModal = false" x-show="showRestartModal" x-transition>
                <h3 class="text-lg font-bold text-gray-900">¿Reiniciar el bot?</h3>
                <p class="mt-2 text-sm text-gray-500">Esta acción forzará el cierre del proceso del bot para limpiar errores. Tardará unos segundos en volver a estar en línea.</p>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showRestartModal = false" class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <form action="{{ route('bot.restart') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-md bg-uncp-gold-web px-4 py-2 text-sm font-semibold text-black hover:bg-uncp-gold-dark">Sí, reiniciar</button>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showLogoutModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl" @click.away="showLogoutModal = false" x-show="showLogoutModal" x-transition>
                <h3 class="text-lg font-bold text-gray-900">¿Desvincular WhatsApp?</h3>
                <p class="mt-2 text-sm text-gray-500">Esta acción borrará la sesión actual. El bot se desconectará y tendrás que volver a escanear el QR.</p>
                <div class="mt-6 flex gap-3">
                    <button type="button" @click="showLogoutModal = false" class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Mantener sesión</button>
                    <form action="{{ route('bot.logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-md bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Sí, desvincular</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusUrl = "{{ route('bot.status') }}";
            const pulse = document.getElementById('wa-pulse');
            const statusText = document.getElementById('wa-status-text');
            const numberText = document.getElementById('wa-number');
            const detailText = document.getElementById('wa-detail');
            const logoutForm = document.getElementById('logout-form');
            const qrLoader = document.getElementById('qr-loader');
            const qrImg = document.getElementById('qr-img');
            const connectedIcon = document.getElementById('connected-icon');
            let intervalId = null;

            function updateUI(data) {
                qrLoader.classList.add('hidden');

                if (data.connected && data.state === 'CONNECTED') {
                    pulse.className = "h-2 w-2 rounded-full bg-uncp-green-logo";
                    statusText.textContent = "Conectado";
                    numberText.textContent = data.number ? `+${data.number}` : 'Vínculo activo';
                    detailText.textContent = "El bot se encuentra respondiendo consultas.";
                    logoutForm.classList.remove('hidden');
                    qrImg.classList.add('hidden');
                    connectedIcon.classList.remove('hidden');
                    resetInterval(30000);
                } else if (data.qrCode) {
                    pulse.className = "h-2 w-2 rounded-full bg-uncp-gold-web animate-pulse";
                    statusText.textContent = "QR listo";
                    numberText.textContent = "Sin vincular";
                    detailText.textContent = "Escanea el código QR para activar el bot.";
                    logoutForm.classList.add('hidden');
                    connectedIcon.classList.add('hidden');
                    qrImg.src = data.qrCode;
                    qrImg.classList.remove('hidden');
                    resetInterval(4000);
                } else {
                    pulse.className = "h-2 w-2 rounded-full bg-rose-500 animate-pulse";
                    statusText.textContent = data.state || "Offline";
                    numberText.textContent = "Desconectado";
                    detailText.textContent = data.error || `Estado: ${data.state || '...'}`;
                    logoutForm.classList.add('hidden');
                    qrImg.classList.add('hidden');
                    connectedIcon.classList.add('hidden');
                    qrLoader.classList.remove('hidden');
                    resetInterval(5000);
                }
            }

            function checkStatus() {
                fetch(statusUrl)
                    .then(response => response.json())
                    .then(data => updateUI(data))
                    .catch(() => updateUI({ connected: false, state: 'ERROR' }));
            }

            function resetInterval(ms) {
                if (intervalId) clearInterval(intervalId);
                intervalId = setInterval(checkStatus, ms);
            }

            checkStatus();
            intervalId = setInterval(checkStatus, 5000);
        });
    </script>
</x-app-layout>
