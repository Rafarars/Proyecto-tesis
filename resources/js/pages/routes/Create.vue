<template>
    <AppLayout title="Crear Nueva Ruta" :breadcrumbs="breadcrumbs">
        <Head title="Crear Ruta" />

        <!-- Header -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nueva Ruta</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Define los puntos de recolección y configura la ruta optimizada</p>
                </div>
                <Link
                    :href="route('routes.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Volver
                </Link>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Básica</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configura los datos principales de la ruta</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="w-full">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre de la Ruta</label>
                                    <input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                        placeholder="Ej: Ruta Centro Norte"
                                        required
                                    />
                                    <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Recolección</label>
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                        required
                                    >
                                        <option value="">Seleccionar tipo</option>
                                        <option value="recoleccion_residencial">Recolección Residencial</option>
                                        <option value="recoleccion_comercial">Recolección Comercial</option>
                                        <option value="recoleccion_mixta">Recolección Mixta</option>
                                    </select>
                                    <div v-if="form.errors.type" class="mt-2 text-sm text-red-600">{{ form.errors.type }}</div>
                                </div>

                                <div class="lg:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                                    <textarea
                                        id="description"
                                        v-model="form.description"
                                        rows="3"
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors resize-none"
                                        placeholder="Descripción opcional de la ruta..."
                                    ></textarea>
                                    <div v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collection Points -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 pb-4 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Puntos de Recolección</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Marca los puntos en el mapa y optimiza la ruta</p>
                    <div v-if="form.errors.collection_points" class="mt-3 text-sm text-red-600">{{ form.errors.collection_points }}</div>
                </div>

                <!-- Interactive Map - Full Width -->
                <div class="w-full">
                    <RouteMap
                        v-model="form.collection_points"
                        @point-added="onPointAdded"
                        @point-removed="onPointRemoved"
                        @route-optimized="onRouteOptimized"
                    />
                </div>
            </div>

            <!-- Schedule Configuration -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Configuración de Horarios</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Define los horarios y días de operación</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hora de Inicio</label>
                                    <input
                                        id="start_time"
                                        v-model="form.start_time"
                                        type="time"
                                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                    />
                                    <div v-if="form.errors.start_time" class="mt-2 text-sm text-red-600">{{ form.errors.start_time }}</div>
                                </div>

                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hora de Fin Estimada</label>
                                    <input
                                        id="end_time"
                                        v-model="form.end_time"
                                        type="time"
                                        class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                    />
                                    <div v-if="form.errors.end_time" class="mt-2 text-sm text-red-600">{{ form.errors.end_time }}</div>
                                </div>

                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Días de la Semana</label>
                                    <div class="grid grid-cols-7 gap-2">
                                        <label v-for="(day, index) in daysOfWeek" :key="index" class="flex items-center">
                                            <input
                                                v-model="form.days_of_week"
                                                :value="index"
                                                type="checkbox"
                                                class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                            />
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ day }}</span>
                                        </label>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Assignment -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Asignación de Vehículo</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Selecciona un vehículo para esta ruta (opcional)</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">

                            <div>
                                <label for="assigned_vehicle_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vehículo</label>
                                <select
                                    id="assigned_vehicle_id"
                                    v-model="form.assigned_vehicle_id"
                                    class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                >
                                    <option value="">Sin asignar (se puede asignar después)</option>
                                    <option v-for="vehicle in availableVehicles" :key="vehicle.id" :value="vehicle.id">
                                        {{ vehicle.license_plate }} - {{ vehicle.code }} ({{ vehicle.type }})
                                    </option>
                                </select>
                                <div v-if="form.errors.assigned_vehicle_id" class="mt-2 text-sm text-red-600">{{ form.errors.assigned_vehicle_id }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-end space-x-4">
                                <Link
                                    :href="route('routes.index')"
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-500 rounded-lg font-semibold text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing || form.collection_points.length < 2"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 border border-transparent rounded-lg font-bold text-sm text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transition-all duration-200"
                                >
                                    <span v-if="form.processing">Creando...</span>
                                    <span v-else>Crear Ruta</span>
                                </button>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import RouteMap from '@/components/Routes/RouteMap.vue'
import {
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

interface Vehicle {
    id: number
    license_plate: string
    code: string
    type: string
    capacity_tons: number
    full_name: string
}

interface Props {
    availableVehicles: Vehicle[]
}

const props = defineProps<Props>()

const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Rutas Optimizadas',
        href: '/routes'
    },
    {
        title: 'Crear Nueva Ruta',
        href: '/routes/create'
    }
]

const form = useForm({
    name: '',
    description: '',
    type: '',
    collection_points: [] as Array<{
        lat: number
        lng: number
        address: string
        priority: number
    }>,
    start_time: '',
    end_time: '',
    days_of_week: [] as number[],
    assigned_vehicle_id: null as number | null,
})

const onPointAdded = (point: any) => {
    // El punto ya se agregó automáticamente al v-model
    console.log('Punto agregado:', point)
}

const onPointRemoved = (index: number) => {
    // El punto ya se removió automáticamente del v-model
    console.log('Punto removido en índice:', index)
}

const onRouteOptimized = (optimizedPoints: any[]) => {
    // Los puntos ya se actualizaron automáticamente en el v-model
    console.log('Ruta optimizada:', optimizedPoints)
}

const submit = () => {
    form.post(route('routes.store'))
}
</script>
