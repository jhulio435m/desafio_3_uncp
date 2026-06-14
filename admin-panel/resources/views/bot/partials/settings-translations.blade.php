<div x-show="activeTab === 'translations'" x-cloak class="grid gap-6 xl:grid-cols-4" x-transition>
                <aside class="space-y-4 xl:col-span-1">
                    <section class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Grupos de textos</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Selecciona el bloque que quieres editar.</p>
                        </div>
                        <div class="space-y-2">
                            @foreach ($translationGroupDefinitions as $group)
                                <button
                                    @click="setActiveGroup('{{ $group['key'] }}')"
                                    :class="activeGroup === '{{ $group['key'] }}' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-500/40 dark:bg-emerald-500/10 dark:text-emerald-200' : 'border-gray-200 bg-gray-50 text-gray-700 hover:border-gray-300 hover:bg-gray-100 dark:border-slate-700 dark:bg-slate-900/50 dark:text-gray-200 dark:hover:border-slate-600 dark:hover:bg-slate-900'"
                                    class="flex w-full items-start justify-between gap-3 rounded-xl border px-3 py-3 text-left transition"
                                >
                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold">{{ $group['label'] }}</span>
                                        <span class="mt-0.5 block text-[11px] text-gray-500 dark:text-gray-400">{{ $group['hint'] }}</span>
                                    </span>
                                    <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-semibold text-gray-500 shadow-sm dark:bg-slate-800 dark:text-gray-300">{{ $translationGroupCounts[$group['key']] ?? 0 }}</span>
                                </button>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Uso operativo</p>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Esta vista está pensada para editar textos en lote sin perder el contexto del grupo ni la clave activa.</p>
                    </section>
                </aside>

                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 xl:col-span-3">
                    <div class="flex flex-col gap-3 border-b border-gray-200 p-4 dark:border-slate-700 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Editor multilingüe</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Busca una clave, abre la ficha y guarda la traducción en español, quechua o asháninka.</p>
                        </div>
                        <div class="w-full lg:max-w-sm">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="searchQuery"
                                    placeholder="Buscar clave o etiqueta..."
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 pl-10 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-100"
                                >
                                <svg class="absolute left-3 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid min-h-[560px] grid-cols-1 gap-0 lg:grid-cols-3">
                        <div class="border-b border-gray-200 bg-gray-50/60 dark:border-slate-700 dark:bg-slate-900/40 lg:border-b-0 lg:border-r">

                            <div class="max-h-[560px] divide-y divide-gray-100 overflow-y-auto dark:divide-slate-700">
                                @foreach ($translationKeys as $keyItem)
                                    <div
                                        x-show="activeGroup === '{{ $keyItem->group }}' && matchesQuery(@js(Str::lower($keyItem->key)), @js(Str::lower($keyItem->label)))"
                                        @click="selectKey({{ $keyItem->id }})"
                                        :class="selectedKeyId === {{ $keyItem->id }} ? 'bg-white border-l-4 border-emerald-500 dark:bg-slate-800' : 'cursor-pointer border-l-4 border-transparent hover:bg-white/70 dark:hover:bg-slate-800/70'"
                                        class="px-4 py-3 transition"
                                    >
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="truncate rounded-full bg-gray-200 px-2 py-0.5 text-[10px] font-semibold text-gray-700 dark:bg-slate-700 dark:text-gray-200 flex items-center gap-1">
        <div class="h-1.5 w-1.5 rounded-full" :class="keys.find(k => k.id === {{ $keyItem->id }})?.is_complete ? 'bg-emerald-500' : 'bg-rose-500'"></div>
        {{ $keyItem->key }}
    </span>
                                            <button
                                                type="button"
                                                @click.stop="navigator.clipboard?.writeText('{{ $keyItem->key }}')"
                                                class="text-[11px] font-semibold text-gray-400 transition hover:text-emerald-600 dark:hover:text-emerald-300"
                                                title="Copiar clave"
                                            >
                                                Copiar
                                            </button>
                                        </div>
                                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $keyItem->label }}</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            ES: {{ Str::limit($keyItem->translations->where('lang', 'es')->first()?->value ?? '', 54) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="lg:col-span-2 p-5">
                            @foreach ($translationKeys as $keyItem)
                                <div x-show="selectedKeyId === {{ $keyItem->id }}" x-cloak class="h-full" x-transition>
                                    <div class="mb-4 flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 pb-4 dark:border-slate-700">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">{{ $keyItem->key }}</span>
                                                <span class="text-[11px] text-gray-400 dark:text-gray-500">Grupo: {{ $keyItem->group }}</span>
                                            </div>
                                            <h4 class="mt-2 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $keyItem->label }}</h4>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $keyItem->description ?: 'Sin descripción' }}</p>
                                        </div>
                                        @if(str_contains($keyItem->translations->where('lang', 'es')->first()?->value ?? '', '{ticket}'))
                                            <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">Incluye variable {ticket}</span>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('bot.translations.update', $keyItem) }}" class="space-y-4" @submit.prevent="submitForm">
                                        @csrf
                                        @method('PATCH')

                                        <div class="grid gap-4">
                                            <div>
                                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Español (ES)</label>
                                                <textarea name="values[es]" rows="4" class="w-full rounded-2xl border-gray-300 bg-gray-50 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-100" required @input="markDirty" @input="markDirty">{{ $keyItem->translations->where('lang', 'es')->first()?->value }}</textarea>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Quechua (QU)</label>
                                                <textarea name="values[qu]" rows="4" class="w-full rounded-2xl border-gray-300 bg-gray-50 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-100" required @input="markDirty" @input="markDirty">{{ $keyItem->translations->where('lang', 'qu')->first()?->value }}</textarea>
                                            </div>
                                            <div>
                                                <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Asháninka (ASH)</label>
                                                <textarea name="values[ash]" rows="4" class="w-full rounded-2xl border-gray-300 bg-gray-50 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-100" required @input="markDirty" @input="markDirty">{{ $keyItem->translations->where('lang', 'ash')->first()?->value }}</textarea>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-3 border-t border-gray-100 pt-4 dark:border-slate-700">
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Mantén intactas las variables dinámicas y el sentido original de la clave.</p>
                                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar traducciones</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach

                            <div x-show="selectedKeyId === null" x-cloak class="flex h-full min-h-[500px] items-center justify-center text-center">
                                <div class="max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m1.048 9.516a3 3 0 11-3.096-4.912 3 3 0 013.096 4.912zm2.238-1.74a7.978 7.978 0 011.713-3.177V9m0 0H21m-2.115 2.115a3 3 0 10-4.243-4.243m4.243 4.243L19 13m-4.757-4.757L13 9" />
                                    </svg>
                                    <h4 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">Selecciona una clave</h4>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">El editor se cargará aquí cuando hagas clic en una fila de la lista.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            