<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Panel UNCP') }} — Proyección Social</title>

        <!-- Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        <script>
            (() => {
                const storedTheme = localStorage.getItem('admin-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-uncp-bg text-uncp-gray-dark">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-uncp-bg lg:flex">
            <!-- Mobile overlay -->
            <div
                x-cloak
                x-show="sidebarOpen"
                x-transition.opacity
                class="fixed inset-0 z-30 bg-black/50 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-40 flex h-screen w-64 -translate-x-full flex-col bg-uncp-green shadow-xl transition-transform duration-200 lg:sticky lg:inset-y-auto lg:left-auto lg:top-0 lg:translate-x-0"
                :class="{ 'translate-x-0': sidebarOpen }"
            >
                <!-- Logo Header -->
                <div class="flex h-16 flex-shrink-0 items-center gap-3 border-b border-white/10 px-5">
                    <a class="flex items-center gap-3" href="{{ route('dashboard') }}">
                        <x-application-logo class="h-9 w-9 drop-shadow" />
                        <div>
                            <span class="block text-xs font-bold uppercase tracking-widest text-uncp-gold leading-tight">UNCP</span>
                            <span class="block text-[11px] font-medium text-white/70 leading-tight">Proyección Social</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="min-h-0 flex-1 overflow-y-auto px-3 py-5 space-y-6">
                    <!-- Monitoreo -->
                    <div>
                        <div class="px-3 pb-1.5 text-[9px] font-bold uppercase tracking-widest text-white/40">
                            Monitoreo
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Panel principal
                                @if(request()->routeIs('dashboard'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Atención Ciudadana -->
                    <div>
                        <div class="px-3 pb-1.5 text-[9px] font-bold uppercase tracking-widest text-white/40">
                            Atención Ciudadana
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('requests.index') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('requests.*') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Solicitudes
                                @if(request()->routeIs('requests.*'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                            <a href="{{ route('bot.human-contacts') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('bot.human-contacts') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                Contacto humano
                                @if(request()->routeIs('bot.human-contacts'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Base de Conocimiento -->
                    <div>
                        <div class="px-3 pb-1.5 text-[9px] font-bold uppercase tracking-widest text-white/40">
                            Base de conocimiento
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('bot.faqs') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('bot.faqs') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Guía del proceso
                                @if(request()->routeIs('bot.faqs'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                            <a href="{{ route('bot.links') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('bot.links') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                Canales oficiales
                                @if(request()->routeIs('bot.links'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Administración -->
                    <div>
                        <div class="px-3 pb-1.5 text-[9px] font-bold uppercase tracking-widest text-white/40">
                            Administración
                        </div>
                        <div class="space-y-0.5">
                            <a href="{{ route('bot.contacts') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('bot.contacts') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Responsables
                                @if(request()->routeIs('bot.contacts'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                            <a href="{{ route('bot.settings') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 {{ request()->routeIs('bot.settings') ? 'bg-white/15 text-white' : 'text-white/65 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Configuración
                                @if(request()->routeIs('bot.settings'))
                                    <span class="ml-auto h-1.5 w-1.5 rounded-full bg-uncp-gold-web"></span>
                                @endif
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Footer -->
                <div class="flex-shrink-0 border-t border-white/10 p-4">
                    <!-- Gold separator line (identidad UNCP) -->
                    <div class="h-px w-full bg-gradient-to-r from-transparent via-uncp-gold/50 to-transparent mb-4"></div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-uncp-gold/20 text-uncp-gold text-xs font-bold uppercase ring-1 ring-uncp-gold/30 flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-white/40 truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('profile.edit') }}" class="flex-1 text-center text-[11px] font-medium text-white/50 hover:text-white/80 transition px-2 py-1.5 rounded hover:bg-white/10">
                            Mi perfil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full text-[11px] font-semibold text-uncp-gold/80 hover:text-uncp-gold px-2 py-1.5 rounded hover:bg-white/10 transition">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="min-w-0 flex-1">
                <!-- Top Header -->
                <header class="sticky top-0 z-20 border-b border-uncp-gold/20 bg-white shadow-sm">
                    <div class="flex h-16 items-center gap-4 px-4 sm:px-6 lg:px-8">
                        <!-- Mobile menu toggle -->
                        <button
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 lg:hidden"
                            type="button"
                            @click="sidebarOpen = true"
                        >
                            <span class="sr-only">Abrir menu</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Gold accent line (identidad UNCP) -->
                        <div class="hidden lg:block h-6 w-0.5 bg-uncp-gold/40 rounded-full"></div>

                        @isset($header)
                            <div class="min-w-0 flex-1">
                                {{ $header }}
                            </div>
                        @endisset

                        <div class="ml-auto flex shrink-0 items-center gap-2">
                            <button
                                type="button"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50"
                                @click="$store.theme.toggle()"
                                :title="$store.theme.dark ? 'Usar modo claro' : 'Usar modo oscuro'"
                                :aria-label="$store.theme.dark ? 'Usar modo claro' : 'Usar modo oscuro'"
                            >
                                <i x-show="!$store.theme.dark" data-lucide="moon" class="h-4 w-4"></i>
                                <i x-show="$store.theme.dark" data-lucide="sun" class="h-4 w-4"></i>
                            </button>

                            <div class="relative" x-data="notificationsPanel('{{ route('notifications.index') }}')" @click.outside="close()">
                                <button
                                    type="button"
                                    class="relative inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50"
                                    title="Notificaciones"
                                    aria-label="Notificaciones"
                                    @click="open = !open"
                                >
                                    <i data-lucide="bell" class="h-4 w-4"></i>
                                    <span
                                        x-cloak
                                        x-show="count > 0"
                                        class="absolute -right-1 -top-1 min-w-5 rounded-full bg-uncp-gold-web px-1.5 py-0.5 text-[10px] font-bold leading-none text-black ring-2 ring-white dark:ring-gray-900"
                                        x-text="count > 9 ? '9+' : count"
                                    ></span>
                                </button>

                                <div
                                    x-cloak
                                    x-show="open"
                                    x-transition
                                    class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl"
                                >
                                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Notificaciones</p>
                                            <p class="text-xs text-gray-500">
                                                <span x-show="updatedAt">Actualizado <span x-text="updatedAt"></span></span>
                                                <span x-show="!updatedAt">Actividad reciente del panel</span>
                                            </p>
                                        </div>
                                        <button type="button" class="rounded-md p-1 text-gray-500 hover:bg-gray-50 hover:text-gray-700" @click="close()" aria-label="Cerrar notificaciones">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </button>
                                    </div>
                                    <div class="divide-y divide-gray-100">
                                        <template x-if="loading">
                                            <div class="px-4 py-5 text-sm text-gray-500">Cargando notificaciones...</div>
                                        </template>

                                        <template x-if="!loading && items.length === 0">
                                            <div class="px-4 py-5 text-sm text-gray-500">No hay solicitudes ni contactos pendientes.</div>
                                        </template>

                                        <template x-for="item in items" :key="item.id">
                                            <a :href="item.url" class="block px-4 py-3 transition hover:bg-gray-50">
                                                <div class="flex items-start gap-3">
                                                    <span
                                                        class="mt-1 h-2 w-2 shrink-0 rounded-full"
                                                        :class="item.type === 'request' ? 'bg-uncp-gold-web' : 'bg-amber-500'"
                                                    ></span>
                                                    <div class="min-w-0">
                                                        <p class="truncate text-sm font-medium text-gray-900" x-text="item.title"></p>
                                                        <p class="mt-0.5 truncate text-xs text-gray-500" x-text="item.description"></p>
                                                        <p class="mt-1 text-[11px] font-medium text-gray-500" x-text="item.time"></p>
                                                    </div>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
