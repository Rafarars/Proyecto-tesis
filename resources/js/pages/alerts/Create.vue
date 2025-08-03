<template>
    <AppLayout title="Nueva Alerta" :breadcrumbs="breadcrumbs">
        <Head title="Nueva Alerta" />

        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Alerta</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Crear una nueva alerta de monitoreo</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('alerts.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <!-- Formulario de creación -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
            <form @submit.prevent="submit" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de Alerta -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Alerta *
                        </label>
                        <select
                            id="type"
                            v-model="form.type"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            required
                        >
                            <option value="">Seleccionar tipo</option>
                            <option value="desvio_ruta">Desvío de Ruta</option>
                            <option value="parada_prolongada">Parada Prolongada</option>
                            <option value="perdida_senal">Pérdida de Señal</option>
                            <option value="fuera_zona">Fuera de Zona</option>
                            <option value="retraso_horario">Retraso en Horario</option>
                            <option value="velocidad_excesiva">Velocidad Excesiva</option>
                            <option value="combustible_bajo">Combustible Bajo</option>
                            <option value="mantenimiento_vencido">Mantenimiento Vencido</option>
                        </select>
                        <div v-if="form.errors.type" class="text-red-600 text-sm mt-1">{{ form.errors.type }}</div>
                    </div>

                    <!-- Prioridad -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prioridad *
                        </label>
                        <select
                            id="priority"
                            v-model="form.priority"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            required
                        >
                            <option value="">Seleccionar prioridad</option>
                            <option value="critica">Crítica</option>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                        <div v-if="form.errors.priority" class="text-red-600 text-sm mt-1">{{ form.errors.priority }}</div>
                    </div>

                    <!-- Vehículo -->
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Vehículo
                        </label>
                        <select
                            id="vehicle_id"
                            v-model="form.vehicle_id"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        >
                            <option value="">Sin vehículo específico</option>
                            <option v-for="vehicle in vehicles" :key="vehicle.id" :value="vehicle.id">
                                {{ vehicle.license_plate }} - {{ vehicle.code }}
                            </option>
                        </select>
                        <div v-if="form.errors.vehicle_id" class="text-red-600 text-sm mt-1">{{ form.errors.vehicle_id }}</div>
                    </div>

                    <!-- Ruta -->
                    <div>
                        <label for="route_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ruta
                        </label>
                        <select
                            id="route_id"
                            v-model="form.route_id"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        >
                            <option value="">Sin ruta específica</option>
                            <option v-for="route in routes" :key="route.id" :value="route.id">
                                {{ route.name }} - {{ route.code }}
                            </option>
                        </select>
                        <div v-if="form.errors.route_id" class="text-red-600 text-sm mt-1">{{ form.errors.route_id }}</div>
                    </div>
                </div>

                <!-- Título -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Título *
                    </label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        placeholder="Título descriptivo de la alerta"
                        required
                    >
                    <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">{{ form.errors.title }}</div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descripción *
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors resize-none"
                        placeholder="Descripción detallada de la alerta"
                        required
                    ></textarea>
                    <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</div>
                </div>

                <!-- Ubicación -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Latitud
                        </label>
                        <input
                            id="latitude"
                            v-model="form.latitude"
                            type="number"
                            step="any"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            placeholder="10.6678"
                        >
                        <div v-if="form.errors.latitude" class="text-red-600 text-sm mt-1">{{ form.errors.latitude }}</div>
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Longitud
                        </label>
                        <input
                            id="longitude"
                            v-model="form.longitude"
                            type="number"
                            step="any"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            placeholder="-63.2583"
                        >
                        <div v-if="form.errors.longitude" class="text-red-600 text-sm mt-1">{{ form.errors.longitude }}</div>
                    </div>

                    <div>
                        <label for="location_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Dirección
                        </label>
                        <input
                            id="location_address"
                            v-model="form.location_address"
                            type="text"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            placeholder="Dirección de la alerta"
                        >
                        <div v-if="form.errors.location_address" class="text-red-600 text-sm mt-1">{{ form.errors.location_address }}</div>
                    </div>
                </div>

                <!-- Asignar a -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Asignar a
                    </label>
                    <select
                        id="assigned_to"
                        v-model="form.assigned_to"
                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                    >
                        <option value="">Sin asignar</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }} - {{ user.email }}
                        </option>
                    </select>
                    <div v-if="form.errors.assigned_to" class="text-red-600 text-sm mt-1">{{ form.errors.assigned_to }}</div>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <Link
                        :href="route('alerts.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ form.processing ? 'Creando...' : 'Crear Alerta' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

interface Props {
    vehicles: Array<any>
    routes: Array<any>
    users: Array<any>
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts'
    },
    {
        title: 'Nueva Alerta',
        href: '/alerts/create'
    }
]

// Formulario
const form = useForm({
    type: '',
    priority: '',
    vehicle_id: '',
    route_id: '',
    title: '',
    description: '',
    latitude: '',
    longitude: '',
    location_address: '',
    assigned_to: '',
})

const submit = () => {
    form.post(route('alerts.store'), {
        onSuccess: () => {
            // Redirigir a la lista de alertas
        }
    })
}
</script>
