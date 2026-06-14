<div x-show="activeTab === 'general'" x-cloak class="grid gap-3 xl:grid-cols-5" x-transition>
                <div class="space-y-4 xl:col-span-3">
                    <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Comportamiento del bot</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Define cómo responde y cuándo delega a atención humana.</p>
                            </div>
                        </div>

                        <div class="grid gap-3 md:grid-cols-2">
                            @if ($aiModeSetting)
                                <form method="POST" action="{{ route('bot.settings.update', $aiModeSetting) }}" class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-slate-700 dark:bg-slate-900/50" @submit.prevent="submitForm">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Modo del asistente</h4>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $aiModeSetting->description }}</p>
                                    </div>
                                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</label>
                                    <div class="flex gap-2">
                                        <select name="value" class="min-w-0 flex-1 rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                                            <option value="activa" {{ $aiModeSetting->value === 'activa' ? 'selected' : '' }}>IA activa</option>
                                            <option value="off" {{ $aiModeSetting->value === 'off' ? 'selected' : '' }}>Respuestas estáticas</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar</button>
                                    </div>
                                </form>
                            @endif

                            @if ($availableSetting)
                                <form method="POST" action="{{ route('bot.settings.update', $availableSetting) }}" class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-slate-700 dark:bg-slate-900/50" @submit.prevent="submitForm">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Mensaje disponible</h4>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $availableSetting->description }}</p>
                                    </div>
                                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Texto</label>
                                    <textarea name="value" rows="2" class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100" required @input="markDirty">{{ $availableSetting->value }}</textarea>
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar</button>
                                    </div>
                                </form>
                            @endif

                            @if ($unavailableSetting)
                                <form method="POST" action="{{ route('bot.settings.update', $unavailableSetting) }}" class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-slate-700 dark:bg-slate-900/50" @submit.prevent="submitForm">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Mensaje fuera de horario</h4>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $unavailableSetting->description }}</p>
                                    </div>
                                    <label class="mb-2 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Texto</label>
                                    <textarea name="value" rows="2" class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100" required @input="markDirty">{{ $unavailableSetting->value }}</textarea>
                                    <div class="mt-2 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-2">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Mensajes operativos</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Textos visibles del flujo, el alcance y los avisos del canal.</p>
                        </div>

                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach ([
                                $welcomeSetting,
                                $languagePromptSetting,
                                $fallbackSetting,
                                $scopeSetting,
                                $officeHoursSetting,
                                $offTopicSetting,
                                $informalSetting,
                                $referencePdfTitleSetting,
                                $referencePdfSentSetting,
                                $referencePdfFailedSetting,
                            ] as $settingItem)
                                @if ($settingItem)
                                    <form method="POST" action="{{ route('bot.settings.update', $settingItem) }}" class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-slate-700 dark:bg-slate-900/50" @submit.prevent="submitForm">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-2">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $settingItem->label }}</h4>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $settingItem->description }}</p>
                                        </div>
                                        <label class="mb-2 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Texto</label>
                                        <textarea name="value" rows="4" class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100" required @input="markDirty">{{ $settingItem->value }}</textarea>
                                        <div class="mt-2 flex justify-end">
                                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar</button>
                                        </div>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </section>

                    @if ($promptSetting)
                        <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <div class="mb-2 flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Prompt del sistema</h3>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $promptSetting->description }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('bot.settings.update', $promptSetting) }}" class="space-y-3" @submit.prevent="submitForm">
                                @csrf
                                @method('PATCH')
                                <textarea name="value" rows="8" class="w-full rounded-2xl border-gray-300 bg-slate-50 font-mono text-xs leading-6 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-100" required @input="markDirty">{{ $promptSetting->value }}</textarea>
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Aquí se controla el tono, idioma y límites de respuesta del bot.</p>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Guardar prompt</button>
                                </div>
                            </form>
                        </section>
                    @endif
                </div>

                <div class="space-y-4 xl:col-span-2">
                    <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-2">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Categorías de conocimiento</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Agrupa FAQs, enlaces y temas del bot para mantener ordenado el contenido.</p>
                        </div>

                        <form method="POST" :action="categoryForm.id ? '{{ url('knowledge-categories') }}/' + categoryForm.id : '{{ route('bot.categories.store') }}'" class="space-y-3 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-slate-700 dark:bg-slate-900/50" @submit.prevent="submitForm">
                            @csrf
                            <template x-if="categoryForm.id">
                                <input type="hidden" name="_method" value="PATCH">
                            </template>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400" x-text="categoryForm.id ? 'Editando categoría' : 'Nueva categoría'"></span>
                                <button type="button" x-show="categoryForm.id" @click="cancelEditCategory()" class="text-[10px] text-gray-500 hover:text-gray-700">Cancelar edición</button>
                            </div>
                            <div class="grid gap-3">
                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nombre</label>
                                    <input name="name" x-model="categoryForm.name" placeholder="Ej. Requisitos, Zootecnia" required class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100" @input="markDirty">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Descripción</label>
                                    <textarea name="description" x-model="categoryForm.description" rows="2" placeholder="Describe la categoría..." class="w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100" @input="markDirty"></textarea>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                    <input name="is_active" type="checkbox" value="1" x-model="categoryForm.is_active" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" @input="markDirty">
                                    Categoría activa
                                </label>
                            </div>
                            <div class="flex justify-end mt-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700" x-text="categoryForm.id ? 'Actualizar categoría' : 'Guardar categoría'"></button>
                            </div>
                        </form>
                    </section>

                    <section class="rounded-2xl border border-gray-200 bg-white p-3 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Categorías registradas</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estado actual de la taxonomía del bot.</p>
                            </div>
                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[11px] font-semibold text-gray-600 dark:bg-slate-900 dark:text-gray-300">{{ $categories->count() }}</span>
                        </div>

                        <div class="max-h-[260px] divide-y divide-gray-100 overflow-y-auto rounded-xl border border-gray-100 dark:divide-slate-700 dark:border-slate-700">
                            @forelse ($categories as $category)
                                <div class="flex items-start justify-between gap-3 px-3 py-2">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</p>
                                        <p class="mt-0.5 line-clamp-2 text-xs text-gray-500 dark:text-gray-400">{{ $category->description ?: 'Sin descripción' }}</p>
                                    </div>
                                    
    <div class="flex items-center gap-2">
        <span class="shrink-0 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold {{ $category->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200' : 'border-gray-200 bg-gray-100 text-gray-600 dark:border-slate-700 dark:bg-slate-900 dark:text-gray-300' }}">
                                        {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
        <div class="flex items-center gap-1 border-l border-gray-200 dark:border-slate-700 pl-2 ml-1">
            <button type="button" @click.stop="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}', {{ $category->is_active ? 'true' : 'false' }})" class="text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 p-1 rounded transition mr-1" title="Editar">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </button>
            <button type="button" @click.stop="if(confirm('¿Eliminar esta categoría?')) { let f=document.createElement('form'); f.action='{{ route('bot.categories.destroy', $category) }}'; f.method='POST'; let m=document.createElement('input'); m.name='_method'; m.value='DELETE'; m.type='hidden'; let c=document.createElement('input'); c.name='_token'; c.value='{{ csrf_token() }}'; c.type='hidden'; f.appendChild(m); f.appendChild(c); document.body.appendChild(f); f.addEventListener('submit', submitForm); f.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true})); }" class="text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 p-1 rounded transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>
    </div>
    
                                </div>
                            @empty
                                <div class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No hay categorías registradas.</div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>

            
