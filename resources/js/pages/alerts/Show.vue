<template>
    <AppLayout title="Detalle de Alerta" :breadcrumbs="breadcrumbs">
        <Head title="Detalle de Alerta" />

        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ alert.title }}</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Código: {{ alert.code }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span :class="statusClasses" class="px-3 py-1 rounded-full text-sm font-medium">
                        {{ alert.status_label }}
                    </span>
                    <span :class="priorityClasses" class="px-3 py-1 rounded-full text-sm font-medium">
                        {{ alert.priority_label }}
                    </span>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detalles de la Alerta -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Información de la Alerta</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.type_label }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioridad</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.priority_label }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.status_label }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Detección</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(alert.detected_at) }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.description }}</p>
                        </div>

                        <div v-if="alert.vehicle" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vehículo</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.vehicle.license_plate }} - {{ alert.vehicle.code }}</p>
                            </div>
                            <div v-if="alert.route">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ruta</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.route.name }}</p>
                            </div>
                        </div>

                        <div v-if="alert.latitude && alert.longitude">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación</label>
                            <div class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <p class="text-sm text-gray-900 dark:text-white">Lat: {{ alert.latitude }}</p>
                                <p class="text-sm text-gray-900 dark:text-white">Lng: {{ alert.longitude }}</p>
                                <p v-if="alert.location_address" class="text-sm text-gray-900 dark:text-white">{{ alert.location_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Acciones</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-3">
                            <button
                                v-if="alert.status === 'activa'"
                                @click="acknowledgeAlert"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Reconocer
                            </button>
                            
                            <button
                                v-if="alert.status !== 'resuelta'"
                                @click="resolveAlert"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Resolver
                            </button>

                            <button
                                v-if="alert.status === 'activa'"
                                @click="dismissAlert"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Descartar
                            </button>

                            <Link
                                :href="route('alerts.edit', alert.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Información de Asignación -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Asignación</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asignado a</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ alert.assigned_user ? alert.assigned_user.name : 'Sin asignar' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creado por</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ alert.created_by_user?.name || 'Sistema' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Creación</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(alert.created_at) }}</p>
                        </div>
                        <div v-if="alert.resolved_at">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Resolución</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(alert.resolved_at) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tiempo de Respuesta</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div v-if="alert.acknowledged_at">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tiempo hasta reconocimiento</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ getResponseTime(alert.detected_at, alert.acknowledged_at) }}</p>
                        </div>
                        <div v-if="alert.resolved_at">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tiempo total de resolución</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ getResponseTime(alert.detected_at, alert.resolved_at) }}</p>
                        </div>
                        <div v-else>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tiempo transcurrido</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ getResponseTime(alert.detected_at, new Date()) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { computed } from 'vue'

interface Props {
    alert: any
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts'
    },
    {
        title: 'Detalle de Alerta',
        href: `/alerts/${props.alert.id}`
    }
]

// Clases CSS para estado y prioridad
const statusClasses = computed(() => {
    const baseClasses = 'px-3 py-1 rounded-full text-sm font-medium'
    switch (props.alert.status) {
        case 'activa':
            return `${baseClasses} bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200`
        case 'reconocida':
            return `${baseClasses} bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200`
        case 'resuelta':
            return `${baseClasses} bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200`
        case 'descartada':
            return `${baseClasses} bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200`
        default:
            return `${baseClasses} bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200`
    }
})

const priorityClasses = computed(() => {
    const baseClasses = 'px-3 py-1 rounded-full text-sm font-medium'
    switch (props.alert.priority) {
        case 'critica':
            return `${baseClasses} bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200`
        case 'alta':
            return `${baseClasses} bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200`
        case 'media':
            return `${baseClasses} bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200`
        case 'baja':
            return `${baseClasses} bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200`
        default:
            return `${baseClasses} bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200`
    }
})

// Funciones de utilidad
const formatDate = (date: string) => {
    return new Date(date).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const getResponseTime = (start: string, end: string | Date) => {
    const startDate = new Date(start)
    const endDate = typeof end === 'string' ? new Date(end) : end
    const diffMs = endDate.getTime() - startDate.getTime()
    
    const hours = Math.floor(diffMs / (1000 * 60 * 60))
    const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60))
    
    if (hours > 0) {
        return `${hours}h ${minutes}m`
    } else {
        return `${minutes}m`
    }
}

// Acciones
const acknowledgeAlert = () => {
    router.patch(route('alerts.acknowledge', props.alert.id))
}

const resolveAlert = () => {
    router.patch(route('alerts.resolve', props.alert.id))
}

const dismissAlert = () => {
    router.patch(route('alerts.dismiss', props.alert.id))
}
</script>
