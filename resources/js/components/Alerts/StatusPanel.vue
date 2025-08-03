<template>
    <div class="space-y-6">
        <!-- Título del panel -->
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Estados de Alertas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gestión y seguimiento del estado de todas las alertas</p>
            </div>
            <div class="flex items-center space-x-3">
                <select
                    v-model="selectedStatus"
                    @change="filterByStatus"
                    class="h-10 px-3 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                >
                    <option value="">Todos los estados</option>
                    <option value="abierta">Abierta</option>
                    <option value="en_atencion">En Atención</option>
                    <option value="resuelta">Resuelta</option>
                    <option value="cerrada">Cerrada</option>
                </select>
            </div>
        </div>

        <!-- Resumen de estados -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-300">Abiertas</p>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ data.status_counts?.abierta || 0 }}</p>
                    </div>
                    <div class="p-2 bg-red-200 dark:bg-red-800 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">En Atención</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ data.status_counts?.en_atencion || 0 }}</p>
                    </div>
                    <div class="p-2 bg-yellow-200 dark:bg-yellow-800 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Resueltas</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ data.status_counts?.resuelta || 0 }}</p>
                    </div>
                    <div class="p-2 bg-green-200 dark:bg-green-800 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20 rounded-lg p-4 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Cerradas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ data.status_counts?.cerrada || 0 }}</p>
                    </div>
                    <div class="p-2 bg-gray-200 dark:bg-gray-800 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl border-l-4 border-gray-400 dark:border-gray-600 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                        <input
                            v-model="searchTerm"
                            type="text"
                            placeholder="Código, título o vehículo..."
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioridad</label>
                        <select
                            v-model="selectedPriority"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        >
                            <option value="">Todas</option>
                            <option value="critica">Crítica</option>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo</label>
                        <select
                            v-model="selectedType"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                        >
                            <option value="">Todos</option>
                            <option value="desvio_ruta">Desvío de Ruta</option>
                            <option value="parada_prolongada">Parada Prolongada</option>
                            <option value="perdida_senal">Pérdida de Señal</option>
                            <option value="fuera_zona">Fuera de Zona</option>
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

        <!-- Tabla de alertas -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl overflow-hidden border-l-4 border-emerald-500 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Lista de Alertas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ filteredAlerts.length }} alertas encontradas</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Asignado a</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detectada</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="filteredAlerts.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No se encontraron alertas</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Intenta ajustar los filtros de búsqueda</p>
                            </td>
                        </tr>
                        <tr v-for="alert in paginatedAlerts" :key="alert.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ alert.code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ alert.type_label }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getPriorityBadgeClass(alert.priority_badge_color)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ alert.priority_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusBadgeClass(alert.status_badge_color)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ alert.status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ alert.vehicle }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ alert.assigned_to }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ alert.detected_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <Link
                                        :href="route('alerts.show', alert.id)"
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
                                        :href="route('alerts.edit', alert.id)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200"
                                        title="Editar"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación simple -->
            <div v-if="totalPages > 1" class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Mostrando {{ startIndex + 1 }} a {{ endIndex }} de {{ filteredAlerts.length }} resultados
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="previousPage"
                            :disabled="currentPage === 1"
                            class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded disabled:opacity-50"
                        >
                            Anterior
                        </button>
                        <button
                            @click="nextPage"
                            :disabled="currentPage === totalPages"
                            class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded disabled:opacity-50"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

interface Props {
    data: {
        alerts?: {
            data: Array<any>
        }
        status_counts?: {
            abierta: number
            en_atencion: number
            resuelta: number
            cerrada: number
        }
    }
}

const props = defineProps<Props>()

// Filtros
const searchTerm = ref('')
const selectedStatus = ref('')
const selectedPriority = ref('')
const selectedType = ref('')

// Paginación
const currentPage = ref(1)
const itemsPerPage = 10

// Computed properties
const alerts = computed(() => props.data.alerts?.data || [])

const filteredAlerts = computed(() => {
    let filtered = alerts.value

    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase()
        filtered = filtered.filter(alert => 
            alert.code.toLowerCase().includes(term) ||
            alert.title.toLowerCase().includes(term) ||
            alert.vehicle.toLowerCase().includes(term)
        )
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(alert => alert.status === selectedStatus.value)
    }

    if (selectedPriority.value) {
        filtered = filtered.filter(alert => alert.priority === selectedPriority.value)
    }

    if (selectedType.value) {
        filtered = filtered.filter(alert => alert.type === selectedType.value)
    }

    return filtered
})

const totalPages = computed(() => Math.ceil(filteredAlerts.value.length / itemsPerPage))
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage)
const endIndex = computed(() => Math.min(startIndex.value + itemsPerPage, filteredAlerts.value.length))

const paginatedAlerts = computed(() => {
    return filteredAlerts.value.slice(startIndex.value, endIndex.value)
})

// Métodos
const clearFilters = () => {
    searchTerm.value = ''
    selectedStatus.value = ''
    selectedPriority.value = ''
    selectedType.value = ''
    currentPage.value = 1
}

const filterByStatus = () => {
    currentPage.value = 1
}

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const getPriorityBadgeClass = (color: string): string => {
    const classes: Record<string, string> = {
        'red': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'orange': 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
        'yellow': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'blue': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300'
    }
    return classes[color] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
}

const getStatusBadgeClass = (color: string): string => {
    const classes: Record<string, string> = {
        'red': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'yellow': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'green': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'gray': 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
    }
    return classes[color] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
}
</script>
