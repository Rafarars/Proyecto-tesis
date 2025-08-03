<template>
    <div class="space-y-6">
        <!-- Título del panel -->
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sistema de Detección Automática</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Monitoreo continuo de la flota y detección de anomalías</p>
            </div>
            <div class="flex items-center space-x-2">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Sistema Activo</span>
                </div>
            </div>
        </div>

        <!-- Estadísticas de detección -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Reglas Activas</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ data.detection_stats?.rules_active || 0 }}</p>
                    </div>
                    <div class="p-2 bg-blue-200 dark:bg-blue-800 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Vehículos Monitoreados</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ data.detection_stats?.vehicles_monitored || 0 }}</p>
                    </div>
                    <div class="p-2 bg-green-200 dark:bg-green-800 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Rutas Monitoreadas</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ data.detection_stats?.routes_monitored || 0 }}</p>
                    </div>
                    <div class="p-2 bg-purple-200 dark:bg-purple-800 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-300">Alertas (24h)</p>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ data.detection_stats?.alerts_last_24h || 0 }}</p>
                    </div>
                    <div class="p-2 bg-orange-200 dark:bg-orange-800 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tipos de alertas detectadas -->
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alertas por Tipo (Última Semana)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div 
                    v-for="(count, type) in alertsByType" 
                    :key="type"
                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                >
                    <div class="flex items-center">
                        <div :class="getTypeIconClass(type)" class="w-8 h-8 rounded-full flex items-center justify-center mr-3">
                            <component :is="getTypeIcon(type)" class="w-4 h-4 text-white" />
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ getTypeLabel(type) }}</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ count }}</span>
                </div>
            </div>
        </div>

        <!-- Configuración de detección -->
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Configuración de Detección</h4>
                <Link
                    :href="route('alerts.index', { panel: 'configuracion' })"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/20 hover:bg-emerald-200 dark:hover:bg-emerald-900/40 transition-colors"
                >
                    <CogIcon class="w-4 h-4 mr-1" />
                    Configurar Reglas
                </Link>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">200m</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Umbral de Desvío</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">30min</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Parada Prolongada</div>
                </div>
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">5min</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pérdida de Señal</div>
                </div>
            </div>
        </div>

        <!-- Sistema de testing -->
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Sistema de Testing</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Simula alertas para validar el funcionamiento del sistema</p>
                </div>
                <button
                    @click="showSimulationModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Simular Alerta
                </button>
            </div>
            
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <p>• Crea escenarios de prueba para validar reglas de detección</p>
                <p>• Simula vehículos fuera de ruta o zona autorizada</p>
                <p>• Genera alertas de testing para verificar notificaciones</p>
            </div>
        </div>

        <!-- Modal de simulación (placeholder) -->
        <div v-if="showSimulationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Simular Alerta</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Funcionalidad de simulación en desarrollo...</p>
                <div class="flex justify-end">
                    <button
                        @click="showSimulationModal = false"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { CogIcon } from '@heroicons/vue/24/outline'
import {
    ExclamationTriangleIcon,
    ClockIcon,
    SignalSlashIcon,
    MapPinIcon,
    TruckIcon,
    BoltIcon,
    WrenchScrewdriverIcon,
    BeakerIcon
} from '@heroicons/vue/24/solid'

interface Props {
    data: {
        detection_stats?: {
            rules_active: number
            vehicles_monitored: number
            routes_monitored: number
            alerts_last_24h: number
        }
        alerts_by_type?: Record<string, number>
    }
}

const props = defineProps<Props>()

const showSimulationModal = ref(false)

const alertsByType = computed(() => {
    return props.data.alerts_by_type || {}
})

const getTypeLabel = (type: string): string => {
    const labels: Record<string, string> = {
        'desvio_ruta': 'Desvío de Ruta',
        'parada_prolongada': 'Parada Prolongada',
        'perdida_senal': 'Pérdida de Señal',
        'fuera_zona': 'Fuera de Zona',
        'retraso_horario': 'Retraso en Horario',
        'velocidad_excesiva': 'Velocidad Excesiva',
        'combustible_bajo': 'Combustible Bajo',
        'mantenimiento_vencido': 'Mantenimiento Vencido'
    }
    return labels[type] || type
}

const getTypeIcon = (type: string) => {
    const icons: Record<string, any> = {
        'desvio_ruta': MapPinIcon,
        'parada_prolongada': ClockIcon,
        'perdida_senal': SignalSlashIcon,
        'fuera_zona': ExclamationTriangleIcon,
        'retraso_horario': ClockIcon,
        'velocidad_excesiva': BoltIcon,
        'combustible_bajo': BeakerIcon,
        'mantenimiento_vencido': WrenchScrewdriverIcon
    }
    return icons[type] || ExclamationTriangleIcon
}

const getTypeIconClass = (type: string): string => {
    const classes: Record<string, string> = {
        'desvio_ruta': 'bg-red-500',
        'parada_prolongada': 'bg-yellow-500',
        'perdida_senal': 'bg-gray-500',
        'fuera_zona': 'bg-orange-500',
        'retraso_horario': 'bg-blue-500',
        'velocidad_excesiva': 'bg-purple-500',
        'combustible_bajo': 'bg-green-500',
        'mantenimiento_vencido': 'bg-indigo-500'
    }
    return classes[type] || 'bg-gray-500'
}
</script>
