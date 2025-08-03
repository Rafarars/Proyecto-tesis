<template>
    <AppLayout title="Detalles de Regla" :breadcrumbs="breadcrumbs">
        <Head title="Detalles de Regla de Alerta" />

        <!-- Header -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ alertRule.name }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Detalles de la regla de detección automática</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('alert-rules.edit', alertRule.id)"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </Link>
                    <Link
                        :href="route('alerts.index', { panel: 'configuracion' })"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <ArrowLeftIcon class="w-4 h-4 mr-2" />
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Básica -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Básica</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Datos principales de la regla</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-slate-800">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ alertRule.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Código</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ alertRule.code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ getTypeLabel(alertRule.type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prioridad</dt>
                                <dd class="mt-1">
                                    <span :class="getPriorityBadgeClass(alertRule.priority)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ getPriorityLabel(alertRule.priority) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-2" v-if="alertRule.description">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ alertRule.description }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Condiciones de Activación -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Condiciones de Activación</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Parámetros que determinan cuándo se activa la regla</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-slate-800">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div v-if="alertRule.threshold_value">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Umbral</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ alertRule.threshold_value }} {{ alertRule.threshold_unit || '' }}
                                </dd>
                            </div>
                            <div v-if="alertRule.threshold_unit">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unidad de Medida</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ getUnitLabel(alertRule.threshold_unit) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Horarios de Activación -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Horarios de Activación</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Cuándo está activa esta regla</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-slate-800">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div v-if="alertRule.active_from">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hora de Inicio</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ alertRule.active_from }}</dd>
                            </div>
                            <div v-if="alertRule.active_to">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hora de Fin</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ alertRule.active_to }}</dd>
                            </div>
                            <div v-if="activeDaysText" class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Días Activos</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ activeDaysText }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Estado de la Regla -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estado de la Regla</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Actual</span>
                                <span :class="alertRule.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ alertRule.is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Creada</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ formatDate(alertRule.created_at) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Actualizada</span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ formatDate(alertRule.updated_at) }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button
                                @click="toggleRule"
                                :class="alertRule.is_active 
                                    ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' 
                                    : 'bg-green-600 hover:bg-green-700 focus:ring-green-500'"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="alertRule.is_active" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ alertRule.is_active ? 'Desactivar Regla' : 'Activar Regla' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>
                        <div class="space-y-3">
                            <Link
                                :href="route('alert-rules.edit', alertRule.id)"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Regla
                            </Link>
                            <button
                                @click="confirmDelete"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Regla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

interface Props {
    alertRule: {
        id: number
        name: string
        code: string
        type: string
        priority: string
        description?: string
        threshold_value?: number
        threshold_unit?: string
        active_from?: string
        active_to?: string
        active_days?: string
        is_active: boolean
        created_at: string
        updated_at: string
    }
}

const props = defineProps<Props>()

const daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts'
    },
    {
        title: 'Configuración de Reglas',
        href: '/alerts?panel=configuracion'
    },
    {
        title: props.alertRule.name,
        href: `/alert-rules/${props.alertRule.id}`
    }
]

// Computed properties
const activeDaysText = computed(() => {
    if (!props.alertRule.active_days) return null
    
    try {
        const days = JSON.parse(props.alertRule.active_days)
        if (!Array.isArray(days) || days.length === 0) return null
        
        return days.map(day => daysOfWeek[day]).join(', ')
    } catch {
        return null
    }
})

// Helper functions
const getTypeLabel = (type: string): string => {
    const types: Record<string, string> = {
        'desvio_ruta': 'Desvío de Ruta',
        'parada_prolongada': 'Parada Prolongada',
        'velocidad_excesiva': 'Velocidad Excesiva',
        'combustible_bajo': 'Combustible Bajo',
        'mantenimiento_vencido': 'Mantenimiento Vencido',
        'zona_restringida': 'Zona Restringida'
    }
    return types[type] || type
}

const getPriorityLabel = (priority: string): string => {
    const priorities: Record<string, string> = {
        'baja': 'Baja',
        'media': 'Media',
        'alta': 'Alta',
        'critica': 'Crítica'
    }
    return priorities[priority] || priority
}

const getPriorityBadgeClass = (priority: string): string => {
    const classes: Record<string, string> = {
        'baja': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
        'media': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'alta': 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
        'critica': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300'
    }
    return classes[priority] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
}

const getUnitLabel = (unit: string): string => {
    const units: Record<string, string> = {
        'metros': 'Metros',
        'kilometros': 'Kilómetros',
        'minutos': 'Minutos',
        'horas': 'Horas',
        'kmh': 'Km/h',
        'litros': 'Litros',
        'porcentaje': 'Porcentaje'
    }
    return units[unit] || unit
}

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Actions
const toggleRule = () => {
    router.patch(route('alert-rules.toggle', props.alertRule.id), {}, {
        preserveState: false,
        onSuccess: () => {
            // La página se recargará con el nuevo estado
        }
    })
}

const confirmDelete = () => {
    if (confirm('¿Estás seguro de que deseas eliminar esta regla? Esta acción no se puede deshacer.')) {
        router.delete(route('alert-rules.destroy', props.alertRule.id), {
            onSuccess: () => {
                router.visit(route('alerts.index', { panel: 'configuracion' }))
            }
        })
    }
}
</script>
