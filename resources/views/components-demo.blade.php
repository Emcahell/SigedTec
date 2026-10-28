<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Componentes Blade') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alerts -->
            <div class="mb-8 space-y-4">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Alertas') }}</h3>

                <x-alert type="success">
                    {{ __('Operación completada exitosamente.') }}
                </x-alert>

                <x-alert type="error">
                    {{ __('Ocurrió un error al procesar la solicitud.') }}
                </x-alert>

                <x-alert type="warning">
                    {{ __('Revise los campos marcados antes de continuar.') }}
                </x-alert>

                <x-alert type="info" dismissible>
                    {{ __('Este mensaje se puede descartar haciendo clic en la X.') }}
                </x-alert>
            </div>

            <!-- Buttons -->
            <div class="mb-8 space-y-4">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Botones') }}</h3>

                <div class="flex flex-wrap gap-4">
                    <x-button variant="primary">
                        {{ __('Primario') }}
                    </x-button>

                    <x-button variant="secondary">
                        {{ __('Secundario') }}
                    </x-button>

                    <x-button variant="danger">
                        {{ __('Peligro') }}
                    </x-button>

                    <x-button variant="success">
                        {{ __('Éxito') }}
                    </x-button>

                    <x-button variant="outline">
                        {{ __('Borde') }}
                    </x-button>

                    <x-button variant="ghost">
                        {{ __('Fantasma') }}
                    </x-button>

                    <x-button size="sm" variant="primary">
                        {{ __('Pequeño') }}
                    </x-button>

                    <x-button size="lg" variant="primary">
                        {{ __('Grande') }}
                    </x-button>

                    <x-button disabled variant="primary">
                        {{ __('Deshabilitado') }}
                    </x-button>

                    <x-button loading variant="primary">
                        {{ __('Cargando...') }}
                    </x-button>
                </div>
            </div>

            <!-- Table -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Tabla de Usuarios') }}</h3>

                <x-table responsive>
                    <x-slot name="header">
                        <x-table-head>
                            <x-table-head-cell sortable sort-field="name" current-sort="name">
                                {{ __('Nombre') }}
                            </x-table-head-cell>
                            <x-table-head-cell>
                                {{ __('Correo') }}
                            </x-table-head-cell>
                            <x-table-head-cell class="w-32">
                                {{ __('Rol') }}
                            </x-table-head-cell>
                            <x-table-head-cell class="w-32 text-right">
                                {{ __('Acciones') }}
                            </x-table-head-cell>
                        </x-table-head>
                    </x-slot>

                    <x-table-row>
                        <x-table-cell>
                            {{ __('Juan Pérez') }}
                        </x-table-cell>
                        <x-table-cell>
                            {{ __('juan@example.com') }}
                        </x-table-cell>
                        <x-table-cell>
                            <span class="px-2 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded">
                                {{ __('Administrador') }}
                            </span>
                        </x-table-cell>
                        <x-table-cell class="text-right">
                            <x-button variant="ghost" size="sm">
                                {{ __('Editar') }}
                            </x-button>
                            <x-button variant="danger" size="sm">
                                {{ __('Eliminar') }}
                            </x-button>
                        </x-table-cell>
                    </x-table-row>

                    <x-table-row>
                        <x-table-cell>
                            {{ __('María García') }}
                        </x-table-cell>
                        <x-table-cell>
                            {{ __('maria@example.com') }}
                        </x-table-cell>
                        <x-table-cell>
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded">
                                {{ __('Usuario') }}
                            </span>
                        </x-table-cell>
                        <x-table-cell class="text-right">
                            <x-button variant="ghost" size="sm">
                                {{ __('Editar') }}
                            </x-button>
                            <x-button variant="danger" size="sm">
                                {{ __('Eliminar') }}
                            </x-button>
                        </x-table-cell>
                    </x-table-row>
                </x-table>
            </div>

            <!-- Modal -->
            <div class="mb-8">
                <x-button variant="primary" @click="openModal = true">
                    {{ __('Abrir Modal') }}
                </x-button>

                <x-modal name="demo-modal" :show="true">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Título del Modal') }}
                        </h3>
                        <div class="mt-4 text-sm text-gray-500">
                            {{ __('Este es un componente modal reutilizable con soporte para Alpine.js.') }}
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 flex items-center gap-2 border-t border-gray-200 sm:px-6">
                        <x-button variant="secondary" @click="openModal = false">
                            {{ __('Cancelar') }}
                        </x-button>
                        <x-button variant="primary" @click="openModal = false">
                            {{ __('Confirmar') }}
                        </x-button>
                    </div>
                </x-modal>
            </div>

        </div>
    </div>
</x-app-layout>
