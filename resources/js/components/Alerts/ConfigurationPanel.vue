<template>
    <div class="space-y-6">
        <!-- Título del panel -->
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configuración de Reglas</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gestión de reglas de detección automática de alertas</p>
            </div>
            <div class="flex items-center space-x-3">
                <Link
                    :href="route('alerts.rules.create')"
                    class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Regla
                </Link>
            </div>
        </div>

        <!-- Resumen de reglas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Reglas Activas</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ activeRulesCount }}</p>
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
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Reglas Inactivas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ inactiveRulesCount }}</p>
                    </div>
                    <div class="p-2 bg-gray-200 dark:bg-gray-800 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-300">Críticas</p>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ criticalRulesCount }}</p>
                    </div>
                    <div class="p-2 bg-red-200 dark:bg-red-800 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Reglas</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ alertRules.length }}</p>
                    </div>
                    <div class="p-2 bg-blue-200 dark:bg-blue-800 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de reglas -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl overflow-hidden border-l-4 border-emerald-500 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reglas de Detección</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ alertRules.length }} reglas configuradas</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Horario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Días</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="alertRules.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay reglas configuradas</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando tu primera regla de detección</p>
                                <div class="mt-6">
                                    <Link
                                        :href="route('alerts.rules.create')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Nueva Regla
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="rule in alertRules" :key="rule.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ rule.name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ rule.code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ rule.type_label }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getPriorityBadgeClass(rule.priority_badge_color)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ rule.priority_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusBadgeClass(rule.status_badge_color)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ rule.status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ rule.formatted_active_hours }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ rule.formatted_active_days }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button
                                        @click="viewRule(rule)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 dark:text-blue-400 dark:bg-blue-950/50 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-colors duration-200"
                                        title="Ver detalles"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </button>
                                    <button
                                        @click="editRule(rule)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200"
                                        title="Editar"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </button>
                                    <button
                                        @click="toggleRule(rule)"
                                        :class="rule.is_active
                                            ? 'inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-950/50 dark:border-red-800 dark:hover:bg-red-900/50 transition-colors duration-200'
                                            : 'inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200'"
                                        :title="rule.is_active ? 'Desactivar regla' : 'Activar regla'"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path v-if="rule.is_active" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ rule.is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reglas predefinidas -->
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Reglas Predefinidas</h4>
                <button
                    @click="createPredefinedRules"
                    class="inline-flex items-center px-3 py-2 border border-emerald-300 dark:border-emerald-600 text-sm leading-4 font-medium rounded-md text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Reglas Básicas
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Desvío de Ruta</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detecta cuando un vehículo se aleja más de 200m de la ruta asignada</p>
                    <div class="text-xs text-gray-500 dark:text-gray-500">
                        • Prioridad: Alta<br>
                        • Umbral: 200 metros<br>
                        • Notificación inmediata
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Parada Prolongada</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Alerta cuando un vehículo permanece inmóvil más de 30 minutos</p>
                    <div class="text-xs text-gray-500 dark:text-gray-500">
                        • Prioridad: Media<br>
                        • Umbral: 30 minutos<br>
                        • Escalamiento automático
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Pérdida de Señal</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Detecta cuando se pierde la comunicación GPS por más de 5 minutos</p>
                    <div class="text-xs text-gray-500 dark:text-gray-500">
                        • Prioridad: Crítica<br>
                        • Umbral: 5 minutos<br>
                        • Notificación inmediata
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h5 class="font-medium text-gray-900 dark:text-white mb-2">Fuera de Zona</h5>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Alerta cuando un vehículo sale de la zona de operación autorizada</p>
                    <div class="text-xs text-gray-500 dark:text-gray-500">
                        • Prioridad: Alta<br>
                        • Zona: Área municipal<br>
                        • Contacto inmediato
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'

interface Props {
    data: {
        alert_rules?: Array<any>
    }
}

const props = defineProps<Props>()



const alertRules = computed(() => props.data.alert_rules || [])

const activeRulesCount = computed(() => alertRules.value.filter(rule => rule.is_active).length)
const inactiveRulesCount = computed(() => alertRules.value.filter(rule => !rule.is_active).length)
const criticalRulesCount = computed(() => alertRules.value.filter(rule => rule.priority_label === 'Crítica').length)

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
        'green': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
        'gray': 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
    }
    return classes[color] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
}

const viewRule = (rule: any) => {
    router.visit(route('alert-rules.show', rule.id))
}

const editRule = (rule: any) => {
    router.visit(route('alert-rules.edit', rule.id))
}

const toggleRule = (rule: any) => {
    router.patch(route('alert-rules.toggle', rule.id), {}, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            // La página se actualizará automáticamente con el nuevo estado
        },
        onError: (errors) => {
            console.error('Error al cambiar estado de la regla:', errors)
        }
    })
}

const createPredefinedRules = () => {
    console.log('Crear reglas predefinidas')
    // Implementar creación de reglas básicas
}
</script>
