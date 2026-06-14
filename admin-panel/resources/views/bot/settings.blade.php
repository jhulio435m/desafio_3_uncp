<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">Configuración del orientador</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ajusta el comportamiento del bot, sus textos multilingües, categorías y mensajes operativos desde un panel más legible.</p>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @php
        $aiModeSetting = $settings->firstWhere('key', 'ai_mode');
        $welcomeSetting = $settings->firstWhere('key', 'welcome_message');
        $languagePromptSetting = $settings->firstWhere('key', 'language_prompt');
        $fallbackSetting = $settings->firstWhere('key', 'fallback_message');
        $scopeSetting = $settings->firstWhere('key', 'scope_message');
        $officeHoursSetting = $settings->firstWhere('key', 'office_hours');
        $offTopicSetting = $settings->firstWhere('key', 'off_topic_message');
        $informalSetting = $settings->firstWhere('key', 'informal_message');
        $referencePdfTitleSetting = $settings->firstWhere('key', 'reference_pdf_title');
        $referencePdfSentSetting = $settings->firstWhere('key', 'reference_pdf_sent_message');
        $referencePdfFailedSetting = $settings->firstWhere('key', 'reference_pdf_failed_message');
        $promptSetting = $settings->firstWhere('key', 'system_prompt');
        $availableSetting = $settings->firstWhere('key', 'human_available_message');
        $unavailableSetting = $settings->firstWhere('key', 'human_unavailable_message');
        $translationGroupCounts = $translationKeys->groupBy('group')->map->count();
        $translationKeysPayload = $translationKeys->map(function ($keyItem) {
            return [
                'id' => $keyItem->id,
                'key' => $keyItem->key,
                'label' => $keyItem->label,
                'group' => $keyItem->group,
                'is_complete' => (bool)($keyItem->translations->where('lang', 'es')->first()?->value && $keyItem->translations->where('lang', 'qu')->first()?->value && $keyItem->translations->where('lang', 'ash')->first()?->value),
            ];
        })->values();
        $translationGroupDefinitions = [
            ['key' => 'welcome_scope', 'label' => 'Bienvenida y alcance', 'hint' => 'Texto inicial y límites del bot.'],
            ['key' => 'menus', 'label' => 'Menús de navegación', 'hint' => 'Opciones y rutas visibles.'],
            ['key' => 'validations', 'label' => 'Mensajes de validación', 'hint' => 'Errores, avisos y confirmaciones.'],
            ['key' => 'wizard_humano', 'label' => 'Contacto humano', 'hint' => 'Derivación a atención humana.'],
            ['key' => 'wizard_registro', 'label' => 'Registro de solicitud', 'hint' => 'Captura de datos y ticket.'],
            ['key' => 'tracking', 'label' => 'Seguimiento y ticket', 'hint' => 'Estado, ticket y consulta posterior.'],
        ];
    @endphp

    <div
        class="py-6"
        x-data="botSettingsEditor(@js($translationKeysPayload))"
    >
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('bot.partials.status')

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

                <div class="px-5 py-4">
                    <div class="inline-flex rounded-xl bg-gray-100 p-1 dark:bg-slate-900">
                        <button
                            @click="setActiveTab('general')"
                            :class="activeTab === 'general' ? 'bg-white text-emerald-700 shadow-sm dark:bg-slate-800 dark:text-emerald-300' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            General
                        </button>
                        <button
                            @click="setActiveTab('translations')"
                            :class="activeTab === 'translations' ? 'bg-white text-emerald-700 shadow-sm dark:bg-slate-800 dark:text-emerald-300' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.516a3 3 0 11-3.096-4.912 3 3 0 013.096 4.912zm2.238-1.74a7.978 7.978 0 011.713-3.177V9m0 0H21m-2.115 2.115a3 3 0 10-4.243-4.243m4.243 4.243L19 13m-4.757-4.757L13 9" />
                            </svg>
                            Traducciones
                        </button>
                    </div>
                </div>
            </section>

                        @include('bot.partials.settings-general')

            @include('bot.partials.settings-translations')
</div>
        </div>
    </div>
        <!-- Toast Notification -->
    <div x-show="toast.show" x-transition x-cloak class="fixed bottom-4 right-4 z-50 rounded-xl px-4 py-3 shadow-lg text-sm font-semibold flex items-center gap-2 text-white" :class="toast.type === 'error' ? 'bg-rose-600' : 'bg-emerald-600'">
        <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        <span x-text="toast.message"></span>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('botSettingsEditor', (keysPayload) => ({
                activeTab: localStorage.getItem('settings-active-tab') || 'general',
                activeGroup: localStorage.getItem('settings-active-group') || 'welcome_scope',
                searchQuery: '',
                selectedKeyId: localStorage.getItem('settings-selected-key-id') ? parseInt(localStorage.getItem('settings-selected-key-id')) : null,
                keys: keysPayload,
                toast: { show: false, message: '', type: 'success' },
                isSubmitting: false,
                isDirty: false,
                categoryForm: { id: null, name: '', description: '', is_active: true },
                editCategory(id, name, desc, isActive) {
                    this.categoryForm.id = id;
                    this.categoryForm.name = name;
                    this.categoryForm.description = desc;
                    this.categoryForm.is_active = isActive;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                cancelEditCategory() {
                    this.categoryForm = { id: null, name: '', description: '', is_active: true };
                },

                init() {
                    if (!this.selectedKeyId || !this.keys.some(k => k.id === this.selectedKeyId && k.group === this.activeGroup)) {
                        this.selectFirstKeyOfGroup(this.activeGroup);
                    }
                    this.$watch('selectedKeyId', () => this.markClean());
                },

                markDirty() { this.isDirty = true; },
                markClean() { this.isDirty = false; },

                confirmLeave() {
                    if (this.isDirty) { return confirm('Tienes cambios sin guardar. ¿Deseas descartarlos?'); }
                    return true;
                },

                setActiveTab(tab) {
                    if (!this.confirmLeave()) return;
                    this.activeTab = tab;
                    localStorage.setItem('settings-active-tab', tab);
                },

                setActiveGroup(group) {
                    if (!this.confirmLeave()) return;
                    this.activeGroup = group;
                    localStorage.setItem('settings-active-group', group);
                    this.searchQuery = '';
                    this.selectFirstKeyOfGroup(group);
                },

                matchesQuery(key, label) {
                    if (this.searchQuery.trim() === '') return true;
                    const q = this.searchQuery.toLowerCase();
                    return key.includes(q) || label.includes(q);
                },

                selectKey(id) {
                    if (this.selectedKeyId === id) return;
                    if (!this.confirmLeave()) return;
                    this.selectedKeyId = id;
                    localStorage.setItem('settings-selected-key-id', id);
                },

                selectFirstKeyOfGroup(group) {
                    const keyRow = this.keys.find(k => k.group === group);
                    if (keyRow) { this.selectKey(keyRow.id); } 
                    else { this.selectedKeyId = null; localStorage.removeItem('settings-selected-key-id'); }
                },

                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                async submitForm(event) {
                    const form = event.target;
                    const url = form.action;
                    const method = form.querySelector('input[name="_method"]')?.value || form.method;
                    const formData = new FormData(form);

                    this.isSubmitting = true;
                    try {
                        const response = await fetch(url, {
                            method: method.toUpperCase(),
                            body: formData,
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await response.json();
                        if (response.ok) {
                            this.showToast(data.message || 'Guardado correctamente');
                            this.markClean();
                            if (url.includes('knowledge-categories')) {
                                setTimeout(() => window.location.reload(), 1000);
                            } else if (url.includes('translations')) {
                                // Update local state so indicator turns green
                                const keyItem = this.keys.find(k => k.id === this.selectedKeyId);
                                if (keyItem) { keyItem.is_complete = !!(formData.get('values[es]') && formData.get('values[qu]') && formData.get('values[ash]')); }
                            }
                        } else {
                            this.showToast(data.message || 'Ocurrió un error', 'error');
                        }
                    } catch (error) {
                        this.showToast('Error de conexión', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
