<template>
    <AppLayout title="Gestión de Vehículos" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Vehículos</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Administra y monitorea la flota de vehículos de recolección de desechos sólidos</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <Link
                        :href="route('vehicles.create')"
                        class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Vehículo
                    </Link>
                </div>
            </div>
        </div>

        <div class="mb-6">
                <!-- Filtros y búsqueda -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl mb-6 border-l-4 border-gray-400 dark:border-gray-600 ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                                <input
                                    v-model="filters.search"
                                    type="text"
                                    placeholder="Placa, código o marca..."
                                    class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                                <select
                                    v-model="filters.status"
                                    class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                >
                                    <option value="">Todos</option>
                                    <option value="activo">Activo</option>
                                    <option value="mantenimiento">Mantenimiento</option>
                                    <option value="fuera_servicio">Fuera de Servicio</option>
                                    <option value="reparacion">Reparación</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                                <select
                                    v-model="filters.type"
                                    class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                >
                                    <option value="">Todos</option>
                                    <option value="compactador">Compactador</option>
                                    <option value="camion">Camión</option>
                                    <option value="pickup">Pickup</option>
                                    <option value="van">Van</option>
                                    <option value="volqueta">Volqueta</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button
                                    @click="clearFilters"
                                    class="w-full h-11 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold px-4 rounded-lg transition duration-200"
                                >
                                    Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-green-500 hover:shadow-2xl transition-shadow duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activos</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-yellow-500 hover:shadow-2xl transition-shadow duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900/30 dark:to-yellow-800/30 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mantenimiento</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.maintenance }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-blue-500 hover:shadow-2xl transition-shadow duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Vehículos</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-purple-500 hover:shadow-2xl transition-shadow duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Con Conductor</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.withDriver }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de vehículos -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-green-500 ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Vehículo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Conductor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Ubicación
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Mantenimiento
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="vehicle in filteredVehicles" :key="vehicle.id" class="hover:bg-gray-50 dark:hover:bg-slate-700 border-b border-gray-100 dark:border-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                                     :class="getVehicleTypeColor(vehicle.type)">
                                                    {{ getVehicleTypeIcon(vehicle.type) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ vehicle.code }} - {{ vehicle.license_plate }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ vehicle.brand }} {{ vehicle.model }} ({{ vehicle.year }})
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div v-if="vehicle.driver" class="text-sm text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ vehicle.driver.full_name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400">{{ vehicle.driver.employee_code }}</div>
                                        </div>
                                        <span v-else class="text-sm text-gray-400 dark:text-gray-500 italic">Sin asignar</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="getStatusColor(vehicle.status)">
                                            {{ getStatusText(vehicle.status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div v-if="vehicle.current_location">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ vehicle.current_speed }} km/h
                                            </div>
                                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ formatDate(vehicle.location_updated_at) }}
                                            </div>
                                        </div>
                                        <span v-else class="text-gray-400 dark:text-gray-500 italic">Sin ubicación</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="getMaintenanceStatusColor(vehicle.maintenance_status)">
                                            {{ getMaintenanceStatusText(vehicle.maintenance_status) }}
                                        </span>
                                        <div v-if="vehicle.next_maintenance" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Próximo: {{ formatDate(vehicle.next_maintenance) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <Link
                                                :href="route('vehicles.show', vehicle.id)"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 dark:text-blue-400 dark:bg-blue-950/50 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-colors duration-200"
                                                title="Ver detalles"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </Link>
                                            <Link
                                                :href="route('vehicles.edit', vehicle.id)"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200"
                                                title="Editar"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </Link>
                                            <button
                                                @click="confirmDelete(vehicle)"
                                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-950/50 dark:border-red-800 dark:hover:bg-red-900/50 transition-colors duration-200"
                                                title="Desactivar"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mensaje cuando no hay vehículos -->
                    <div v-if="filteredVehicles.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay vehículos</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ filters.search || filters.status || filters.type ? 'No se encontraron vehículos con los filtros aplicados.' : 'Comienza agregando un nuevo vehículo.' }}
                        </p>
                        <div class="mt-6">
                            <Link
                                :href="route('vehicles.create')"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nuevo Vehículo
                            </Link>
                        </div>
                    </div>
                </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Gestión de Vehículos',
        href: '/vehicles',
        current: true
    }
]

// Props
const props = defineProps({
    vehicles: Array
})

// Estado reactivo
const filters = ref({
    search: '',
    status: '',
    type: ''
})

// Estadísticas computadas
const stats = computed(() => {
    const vehicles = props.vehicles
    return {
        total: vehicles.length,
        active: vehicles.filter(v => v.status === 'activo').length,
        maintenance: vehicles.filter(v => v.status === 'mantenimiento').length,
        withDriver: vehicles.filter(v => v.driver).length
    }
})

// Vehículos filtrados
const filteredVehicles = computed(() => {
    let filtered = props.vehicles

    if (filters.value.search) {
        const search = filters.value.search.toLowerCase()
        filtered = filtered.filter(vehicle =>
            vehicle.license_plate.toLowerCase().includes(search) ||
            vehicle.code.toLowerCase().includes(search) ||
            vehicle.brand.toLowerCase().includes(search) ||
            vehicle.model.toLowerCase().includes(search)
        )
    }

    if (filters.value.status) {
        filtered = filtered.filter(vehicle => vehicle.status === filters.value.status)
    }

    if (filters.value.type) {
        filtered = filtered.filter(vehicle => vehicle.type === filters.value.type)
    }

    return filtered
})

// Métodos
const clearFilters = () => {
    filters.value = {
        search: '',
        status: '',
        type: ''
    }
}

const getVehicleTypeColor = (type) => {
    const colors = {
        compactador: 'bg-blue-500',
        camion: 'bg-green-500',
        pickup: 'bg-yellow-500',
        van: 'bg-purple-500',
        volqueta: 'bg-red-500'
    }
    return colors[type] || 'bg-gray-500'
}

const getVehicleTypeIcon = (type) => {
    const icons = {
        compactador: 'CP',
        camion: 'CM',
        pickup: 'PK',
        van: 'VN',
        volqueta: 'VQ'
    }
    return icons[type] || 'VH'
}

const getStatusColor = (status) => {
    const colors = {
        activo: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700',
        mantenimiento: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700',
        fuera_servicio: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700',
        reparacion: 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-700'
    }
    return colors[status] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
}

const getStatusText = (status) => {
    const texts = {
        activo: 'Activo',
        mantenimiento: 'Mantenimiento',
        fuera_servicio: 'Fuera de Servicio',
        reparacion: 'Reparación'
    }
    return texts[status] || status
}

const getMaintenanceStatusColor = (status) => {
    const colors = {
        al_dia: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700',
        proximo: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700',
        vencido: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700',
        sin_programar: 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
    }
    return colors[status] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
}

const getMaintenanceStatusText = (status) => {
    const texts = {
        al_dia: 'Al día',
        proximo: 'Próximo',
        vencido: 'Vencido',
        sin_programar: 'Sin programar'
    }
    return texts[status] || status
}

const formatDate = (date) => {
    if (!date) return ''
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const confirmDelete = (vehicle) => {
    if (confirm(`¿Estás seguro de que deseas desactivar el vehículo ${vehicle.code}?`)) {
        router.delete(route('vehicles.destroy', vehicle.id), {
            onSuccess: () => {
                // El mensaje de éxito se maneja en el backend
            }
        })
    }
}
</script>
