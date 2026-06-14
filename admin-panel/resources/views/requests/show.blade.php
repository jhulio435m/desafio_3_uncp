<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle de Solicitud') }}: {{ $request->ticket_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                        <p><strong>Ticket ID:</strong> {{ $request->ticket_id }}</p>
                        <p><strong>Fecha de Creación:</strong> {{ $request->created_at }}</p>
                        <p><strong>Institución:</strong> {{ $request->institution_name }} ({{ $request->institution_type }})</p>
                        <p><strong>Ubicación:</strong> {{ $request->location }}</p>
                        <p><strong>Representante:</strong> {{ $request->representative_name }} (DNI: {{ $request->representative_dni }})</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Actualizar Estado</h3>
                        <form action="{{ route('requests.update', $request->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <x-input-label for="status" :value="__('Estado Actual')" />
                                <select name="status" id="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                                    <option value="Recibido" {{ $request->status == 'Recibido' ? 'selected' : '' }}>Recibido</option>
                                    <option value="Evaluando" {{ $request->status == 'Evaluando' ? 'selected' : '' }}>Evaluando</option>
                                    <option value="Asignado" {{ $request->status == 'Asignado' ? 'selected' : '' }}>Asignado</option>
                                    <option value="En Ejecución" {{ $request->status == 'En Ejecución' ? 'selected' : '' }}>En Ejecución</option>
                                    <option value="Finalizado" {{ $request->status == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                            </div>

                            <x-primary-button>
                                {{ __('Actualizar Estado') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Descripción de la Necesidad</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        {{ $request->description }}
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('requests.index') }}" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
