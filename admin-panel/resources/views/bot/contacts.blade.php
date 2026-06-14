<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold leading-tight text-gray-900">Responsables del Proceso</h2>
                <p class="mt-0.5 text-sm text-gray-500">Contactos relacionados con la orientación y derivación inicial de proyección social.</p>
            </div>
            <a href="#create-contact-form" onclick="document.getElementById('create-contact-details').setAttribute('open','')" class="inline-flex items-center gap-2 px-4 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo responsable
            </a>
        </div>
    </x-slot>


    <div class="py-8" x-data="contactsManager()">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('bot.partials.status')

            <!-- Creation Form: Collapsible Card -->
            <details id="create-contact-details" class="group bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                <summary class="flex justify-between items-center font-bold px-6 py-4 cursor-pointer select-none hover:bg-gray-50/80 list-none">
                    <span class="text-gray-800 flex items-center gap-2 text-sm">
                        <svg class="h-4 w-4 text-uncp-green" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Agregar nuevo responsable
                    </span>
                    <span class="transition group-open:rotate-180">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </summary>
                <div class="px-6 pb-6 pt-3 border-t border-gray-100 bg-gray-50/20">
                    <form method="POST" action="{{ route('bot.contacts.store') }}">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Responsable o Canal</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="name" required>
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Oficina / Cargo</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="office">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Teléfono / WhatsApp</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="phone">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Correo electrónico</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="email" type="email">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Horario de atención (Texto libre)</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="attention_hours" placeholder="Ej. Lun-Vie 8:00 AM - 2:00 PM">
                            </div>
                            <div class="flex flex-col space-y-1">
                                <label class="text-xs font-bold text-gray-500 uppercase">Temas del proceso que atiende</label>
                                <input class="rounded-lg border-gray-300 shadow-sm focus:border-uncp-green focus:ring-uncp-green text-sm" name="topics">
                            </div>
                        </div>

                        <!-- Schedule Editor in Creation Form -->
                        <div class="mt-6 border-t border-gray-100 pt-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Disponibilidad Estructurada (Opcional)</h4>
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-50">
                                <template x-for="(dayName, dayNum) in weekDays" :key="'create-'+dayNum">
                                    <div class="p-3 flex flex-col sm:flex-row sm:items-center gap-3">
                                        <div class="w-24 flex items-center gap-2 flex-shrink-0">
                                            <input type="checkbox" x-model="createDayActive[dayNum]" @change="toggleCreateDay(dayNum)" class="rounded border-gray-300 text-uncp-green shadow-sm focus:ring-uncp-green h-4 w-4">
                                            <span class="text-sm font-bold text-gray-700" x-text="dayName"></span>
                                        </div>
                                        <div class="flex-1 flex flex-wrap gap-2 min-h-[32px] items-center">
                                            <template x-if="createDayActive[dayNum]">
                                                <div class="contents">
                                                    <template x-for="(slot, index) in createDaySlots[dayNum]" :key="slot.id">
                                                        <div class="inline-flex items-center gap-1 bg-uncp-bg border border-uncp-gold/50 rounded-lg p-1 pr-1.5">
                                                            <input type="hidden" :name="`schedules[${slot.id}][day_of_week]`" :value="dayNum">
                                                            <input type="time" :name="`schedules[${slot.id}][start_time]`" x-model="slot.start_time" class="border-0 bg-transparent p-0 text-[11px] font-bold text-uncp-green focus:ring-0 w-16">
                                                            <span class="text-[10px] text-uncp-gold font-bold">-</span>
                                                            <input type="time" :name="`schedules[${slot.id}][end_time]`" x-model="slot.end_time" class="border-0 bg-transparent p-0 text-[11px] font-bold text-uncp-green focus:ring-0 w-16">
                                                            <button type="button" @click="removeCreateSlot(dayNum, index)" class="text-uncp-gold hover:text-uncp-green ml-1">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            <span x-show="!createDayActive[dayNum]" class="text-[11px] text-gray-400 italic">No configurado</span>
                                        </div>
                                        <button x-show="createDayActive[dayNum]" type="button" @click="addCreateSlot(dayNum)" class="p-1 text-uncp-green hover:bg-uncp-bg rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <input class="rounded border-gray-300 text-uncp-green focus:ring-uncp-green h-4 w-4" name="is_active" type="checkbox" value="1" checked id="create_is_active">
                                <label for="create_is_active" class="text-sm text-gray-600">Contacto activo por defecto</label>
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-uncp-gold-web hover:bg-uncp-gold-dark text-black text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">Crear contacto</button>
                        </div>
                    </form>
                </div>
            </details>

            {{-- Search & Filters --}}
            <form method="GET" action="{{ route('bot.contacts') }}" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre, cargo o temas…" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green">
                    </div>
                    <select name="active" class="text-sm border border-gray-200 rounded-lg focus:ring-uncp-green focus:border-uncp-green py-2 px-3">
                        <option value="">Activos e inactivos</option>
                        <option value="1" {{ ($selectedActive ?? '') === '1' ? 'selected' : '' }}>Solo activos</option>
                        <option value="0" {{ ($selectedActive ?? '') === '0' ? 'selected' : '' }}>Solo inactivos</option>
                    </select>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-gray-400">{{ $contacts->total() }} resultado(s)</p>
                    <div class="flex gap-2">
                        @if(($search ?? '') || ($selectedActive ?? '') !== '')
                            <a href="{{ route('bot.contacts') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Limpiar</a>
                        @endif
                        <button type="submit" class="text-xs font-semibold text-white bg-uncp-green hover:bg-uncp-green-light px-4 py-1.5 rounded-lg transition shadow-sm">Buscar</button>
                    </div>
                </div>
            </form>

            <!-- Contacts Grid (Better UX) -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider px-1">Responsables Registrados ({{ $contacts->total() }})</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($contacts as $contact)
                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col">
                            <div class="p-5 flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $contact->is_active ? 'bg-uncp-green-logo' : 'bg-gray-300' }}"></span>
                                        <span class="text-xs font-bold uppercase tracking-wider {{ $contact->is_active ? 'text-uncp-green' : 'text-gray-500' }}">
                                            {{ $contact->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                    <div class="flex gap-1">
                                        <button @click="editContact({{ json_encode($contact->load('schedules')) }})" class="p-2 text-gray-400 hover:text-uncp-green hover:bg-uncp-bg rounded-lg transition-colors" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ route('bot.contacts.destroy', $contact) }}" onsubmit="return confirm('¿Estás seguro de eliminar este responsable?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <h4 class="text-lg font-bold text-gray-900 leading-tight mb-1">{{ $contact->name }}</h4>
                                @if($contact->office)
                                    <p class="text-sm font-semibold text-uncp-green mb-4">{{ $contact->office }}</p>
                                @endif

                                <div class="space-y-2.5">
                                    @if($contact->phone)
                                        <div class="flex items-center text-sm text-gray-600 gap-2.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            <span>{{ $contact->phone }}</span>
                                        </div>
                                    @endif
                                    @if($contact->email)
                                        <div class="flex items-center text-sm text-gray-600 gap-2.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <span class="truncate">{{ $contact->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 border-t border-gray-100">
                                <h5 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Horarios
                                </h5>
                                @if($contact->schedules->isEmpty())
                                    <p class="text-xs text-gray-400 italic">No estructurado</p>
                                @else
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($contact->schedules->groupBy('day_of_week')->sortKeys() as $day => $schs)
                                            <div class="inline-flex items-center bg-white border border-gray-200 rounded px-1.5 py-0.5 text-[10px] text-gray-600 font-semibold">
                                                {{ ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'][$day-1] }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500">
                            No hay responsables registrados.
                        </div>
                    @endforelse
                </div>

                {{ $contacts->links() }}
            </div>
        </div>

        <!-- Unified Edit Modal -->
        <x-modal name="edit-contact-modal" :show="$errors->any()" focusable>
            <div class="p-0 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Editar Responsable</h3>
                    <button @click="$dispatch('close-modal', 'edit-contact-modal')" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="px-6 mt-4 flex border-b border-gray-200">
                    <button @click="tab = 'info'" :class="tab === 'info' ? 'border-uncp-gold text-uncp-green' : 'border-transparent text-gray-500'" class="px-4 py-3 border-b-2 font-bold text-sm transition-all duration-200">Información</button>
                    <button @click="tab = 'schedule'" :class="tab === 'schedule' ? 'border-uncp-gold text-uncp-green' : 'border-transparent text-gray-500'" class="px-4 py-3 border-b-2 font-bold text-sm transition-all duration-200">Horarios</button>
                </div>

                <form :action="`/contacts/${editForm.id}`" method="POST">
                    @csrf @method('PATCH')
                    
                    <div x-show="tab === 'info'" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <x-input-label for="edit_name" value="Responsable" />
                                <x-text-input id="edit_name" name="name" type="text" class="block w-full" x-model="editForm.name" required />
                            </div>
                            <div class="space-y-1">
                                <x-input-label for="edit_office" value="Oficina" />
                                <x-text-input id="edit_office" name="office" type="text" class="block w-full" x-model="editForm.office" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <x-input-label for="edit_phone" value="Teléfono" />
                                <x-text-input id="edit_phone" name="phone" type="text" class="block w-full" x-model="editForm.phone" />
                            </div>
                            <div class="space-y-1">
                                <x-input-label for="edit_email" value="Correo" />
                                <x-text-input id="edit_email" name="email" type="email" class="block w-full" x-model="editForm.email" />
                            </div>
                        </div>
                        <div class="space-y-1">
                            <x-input-label for="edit_hours" value="Horario (Texto)" />
                            <x-text-input id="edit_hours" name="attention_hours" type="text" class="block w-full" x-model="editForm.attention_hours" />
                        </div>
                        <div class="space-y-1">
                            <x-input-label for="edit_topics" value="Temas" />
                            <x-text-input id="edit_topics" name="topics" type="text" class="block w-full" x-model="editForm.topics" />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-model="editForm.is_active" class="rounded border-gray-300 text-uncp-green shadow-sm focus:ring-uncp-green">
                            <label for="edit_is_active" class="text-sm font-semibold text-gray-700">Activo</label>
                        </div>
                    </div>

                    <div x-show="tab === 'schedule'" class="p-6 space-y-4 max-h-[50vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Planificación Semanal</p>
                            <div class="flex gap-2">
                                <button type="button" @click="setEditPreset()" class="text-[10px] font-bold text-uncp-green bg-uncp-bg border border-uncp-gold/50 px-2 py-1 rounded">Preajuste L-V</button>
                                <button type="button" @click="clearEditSchedules()" class="text-[10px] font-bold text-gray-500 bg-gray-50 border border-gray-200 px-2 py-1 rounded">Limpiar</button>
                            </div>
                        </div>

                        <div class="border border-gray-100 rounded-xl overflow-hidden divide-y divide-gray-50 bg-white shadow-sm">
                            <template x-for="(dayName, dayNum) in weekDays" :key="'edit-'+dayNum">
                                <div class="p-3 flex flex-col sm:flex-row sm:items-center gap-3 hover:bg-gray-50/50 transition-colors">
                                    <div class="w-24 flex items-center gap-2 flex-shrink-0">
                                        <input type="checkbox" x-model="editDayActive[dayNum]" @change="toggleEditDay(dayNum)" class="rounded border-gray-300 text-uncp-green shadow-sm focus:ring-uncp-green h-4 w-4">
                                        <span class="text-sm font-bold text-gray-800" x-text="dayName"></span>
                                    </div>
                                    <div class="flex-1 flex flex-wrap gap-2 min-h-[32px] items-center">
                                        <template x-if="editDayActive[dayNum]">
                                            <div class="contents">
                                                <template x-for="(slot, index) in editDaySlots[dayNum]" :key="slot.id">
                                                    <div class="inline-flex items-center gap-1 bg-uncp-bg border border-uncp-gold/50 rounded-lg p-1 pr-1.5">
                                                        <input type="hidden" :name="`schedules[${slot.id}][day_of_week]`" :value="dayNum">
                                                        <input type="time" :name="`schedules[${slot.id}][start_time]`" x-model="slot.start_time" class="border-0 bg-transparent p-0 text-[11px] font-bold text-uncp-green focus:ring-0 w-16">
                                                        <span class="text-[10px] text-uncp-gold font-bold">-</span>
                                                        <input type="time" :name="`schedules[${slot.id}][end_time]`" x-model="slot.end_time" class="border-0 bg-transparent p-0 text-[11px] font-bold text-uncp-green focus:ring-0 w-16">
                                                        <button type="button" @click="removeEditSlot(dayNum, index)" class="text-uncp-gold hover:text-uncp-green ml-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <span x-show="!editDayActive[dayNum]" class="text-[11px] text-gray-400 italic">No disponible</span>
                                    </div>
                                    <button x-show="editDayActive[dayNum]" type="button" @click="addEditSlot(dayNum)" class="p-1 text-uncp-green hover:bg-uncp-bg rounded"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <x-secondary-button @click="$dispatch('close-modal', 'edit-contact-modal')">Cancelar</x-secondary-button>
                        <x-primary-button class="bg-uncp-green hover:bg-uncp-green-light shadow-sm">Guardar Cambios</x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    <script>
        function contactsManager() {
            return {
                tab: 'info',
                weekDays: { 1: 'Lunes', 2: 'Martes', 3: 'Miércoles', 4: 'Jueves', 5: 'Viernes', 6: 'Sábado', 7: 'Domingo' },
                
                // Create Form State
                createDayActive: { 1:false, 2:false, 3:false, 4:false, 5:false, 6:false, 7:false },
                createDaySlots: { 1:[], 2:[], 3:[], 4:[], 5:[], 6:[], 7:[] },
                
                // Edit Form State
                editForm: { id: null, name: '', office: '', phone: '', email: '', attention_hours: '', topics: '', is_active: true },
                editDayActive: { 1:false, 2:false, 3:false, 4:false, 5:false, 6:false, 7:false },
                editDaySlots: { 1:[], 2:[], 3:[], 4:[], 5:[], 6:[], 7:[] },

                addCreateSlot(day) {
                    this.createDayActive[day] = true;
                    this.createDaySlots[day].push({ id: 'new_'+Date.now()+'_'+Math.random(), start_time: '08:00', end_time: '13:00' });
                },

                toggleCreateDay(day) {
                    if (this.createDayActive[day] && this.createDaySlots[day].length === 0) this.addCreateSlot(day);
                    else if (!this.createDayActive[day]) this.createDaySlots[day] = [];
                },

                removeCreateSlot(day, index) {
                    this.createDaySlots[day].splice(index, 1);
                    if (this.createDaySlots[day].length === 0) this.createDayActive[day] = false;
                },

                editContact(contact) {
                    this.tab = 'info';
                    this.editForm = { ...contact };
                    this.resetEditSchedules();
                    if (contact.schedules) {
                        contact.schedules.forEach(s => {
                            const d = s.day_of_week;
                            this.editDayActive[d] = true;
                            this.editDaySlots[d].push({
                                id: 'init_'+s.id+'_'+Math.random().toString(36).substr(2,4),
                                start_time: s.start_time.substring(0, 5),
                                end_time: s.end_time.substring(0, 5)
                            });
                        });
                    }
                    this.$dispatch('open-modal', 'edit-contact-modal');
                },

                resetEditSchedules() {
                    for(let i=1; i<=7; i++) { this.editDayActive[i] = false; this.editDaySlots[i] = []; }
                },

                toggleEditDay(day) {
                    if (this.editDayActive[day] && this.editDaySlots[day].length === 0) this.addEditSlot(day);
                    else if (!this.editDayActive[day]) this.editDaySlots[day] = [];
                },

                addEditSlot(day) {
                    this.editDayActive[day] = true;
                    this.editDaySlots[day].push({ id: 'new_'+Date.now()+'_'+Math.random(), start_time: '08:00', end_time: '13:00' });
                },

                removeEditSlot(day, index) {
                    this.editDaySlots[day].splice(index, 1);
                    if (this.editDaySlots[day].length === 0) this.editDayActive[day] = false;
                },

                clearEditSchedules() { if (confirm('¿Limpiar horarios?')) this.resetEditSchedules(); },

                setEditPreset() {
                    this.resetEditSchedules();
                    for(let d=1; d<=5; d++) {
                        this.editDayActive[d] = true;
                        this.editDaySlots[d] = [
                            { id: 'p1_'+d+'_'+Math.random(), start_time: '08:00', end_time: '13:00' },
                            { id: 'p2_'+d+'_'+Math.random(), start_time: '14:00', end_time: '17:00' }
                        ];
                    }
                }
            }
        }
    </script>
</x-app-layout>
