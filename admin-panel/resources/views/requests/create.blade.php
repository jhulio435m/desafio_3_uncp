<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Solicitud de Proyección Social') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Representative Name -->
                            <div>
                                <x-input-label for="representative_name" :value="__('Nombre del Representante')" />
                                <x-text-input id="representative_name" class="block mt-1 w-full" type="text" name="representative_name" :value="old('representative_name', $representative_name)" required autofocus />
                                <x-input-error :messages="$errors->get('representative_name')" class="mt-2" />
                            </div>

                            <!-- Representative DNI -->
                            <div>
                                <x-input-label for="representative_dni" :value="__('DNI del Representante')" />
                                <x-text-input id="representative_dni" class="block mt-1 w-full" type="text" name="representative_dni" :value="old('representative_dni')" required />
                                <x-input-error :messages="$errors->get('representative_dni')" class="mt-2" />
                            </div>

                            <!-- Institution Name -->
                            <div>
                                <x-input-label for="institution_name" :value="__('Nombre de la Institución/Comunidad')" />
                                <x-text-input id="institution_name" class="block mt-1 w-full" type="text" name="institution_name" :value="old('institution_name')" required />
                                <x-input-error :messages="$errors->get('institution_name')" class="mt-2" />
                            </div>

                            <!-- Institution Type -->
                            <div>
                                <x-input-label for="institution_type" :value="__('Tipo de Institución')" />
                                <select id="institution_type" name="institution_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                    <option value="Comunidad Campesina" {{ old('institution_type') == 'Comunidad Campesina' ? 'selected' : '' }}>Comunidad Campesina</option>
                                    <option value="Comunidad Urbana" {{ old('institution_type') == 'Comunidad Urbana' ? 'selected' : '' }}>Comunidad Urbana</option>
                                    <option value="Municipalidad / Gob. Local" {{ old('institution_type') == 'Municipalidad / Gob. Local' ? 'selected' : '' }}>Municipalidad / Gob. Local</option>
                                    <option value="Institución Educativa" {{ old('institution_type') == 'Institución Educativa' ? 'selected' : '' }}>Institución Educativa</option>
                                    <option value="Organización Social" {{ old('institution_type') == 'Organización Social' ? 'selected' : '' }}>Organización Social / Asociación</option>
                                    <option value="Otro" {{ old('institution_type') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <x-input-error :messages="$errors->get('institution_type')" class="mt-2" />
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <x-input-label for="location" :value="__('Ubicación (Distrito, Centro Poblado, Dirección)')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Descripción detallada de la necesidad')" />
                                <textarea id="description" name="description" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm" required>{{ old('description', $description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Initial Status -->
                            <div>
                                <x-input-label for="status" :value="__('Estado Inicial')" />
                                <select id="status" name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm">
                                    <option value="Recibido" {{ old('status', 'Recibido') == 'Recibido' ? 'selected' : '' }}>Recibido</option>
                                    <option value="Evaluando" {{ old('status') == 'Evaluando' ? 'selected' : '' }}>Evaluando</option>
                                    <option value="Asignado" {{ old('status') == 'Asignado' ? 'selected' : '' }}>Asignado</option>
                                    <option value="En Ejecución" {{ old('status') == 'En Ejecución' ? 'selected' : '' }}>En Ejecución</option>
                                    <option value="Finalizado" {{ old('status') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 gap-4">
                            <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Guardar Solicitud') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
