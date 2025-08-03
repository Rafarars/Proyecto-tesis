<template>
    <AppLayout title="Alertas y Monitoreo" :breadcrumbs="breadcrumbs">
        <Head title="Alertas y Monitoreo" />

        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Alertas y Monitoreo</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Sistema de detección y gestión de incidencias en tiempo real</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('alerts.create')"
                        class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Alerta
                    </Link>
                </div>
            </div>
        </div>

        <!-- Estadísticas generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Alertas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-blue-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Alertas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_alerts }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl shadow-md">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alertas Activas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-red-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alertas Activas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.active_alerts }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 rounded-xl shadow-md">
                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alertas Críticas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-orange-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Críticas Activas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.critical_alerts }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl shadow-md">
                        <svg class="h-8 w-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Resueltas Hoy -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-green-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Resueltas Hoy</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.resolved_today }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl shadow-md">
                        <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación por subpaneles -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button
                        v-for="panel in panels"
                        :key="panel.id"
                        @click="changePanel(panel.id)"
                        :class="[
                            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                            activePanel === panel.id
                                ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                        ]"
                    >
                        <div class="flex items-center">
                            <component :is="panel.icon" class="w-5 h-5 mr-2" />
                            {{ panel.name }}
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Contenido del panel activo -->
            <div class="p-6">
                <!-- Panel de Detección Automática -->
                <DetectionPanel 
                    v-if="activePanel === 'deteccion'" 
                    :data="panelData" 
                />

                <!-- Panel de Monitoreo en Tiempo Real -->
                <MonitoringPanel 
                    v-if="activePanel === 'monitoreo'" 
                    :data="panelData"
                    :recent-alerts="recentAlerts"
                />

                <!-- Panel de Estados de Alertas -->
                <StatusPanel 
                    v-if="activePanel === 'estados'" 
                    :data="panelData" 
                />

                <!-- Panel de Gestión de Incidencias -->
                <IncidentsPanel 
                    v-if="activePanel === 'incidencias'" 
                    :data="panelData" 
                />

                <!-- Panel de Configuración de Reglas -->
                <ConfigurationPanel 
                    v-if="activePanel === 'configuracion'" 
                    :data="panelData" 
                />
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import DetectionPanel from '@/components/Alerts/DetectionPanel.vue'
import MonitoringPanel from '@/components/Alerts/MonitoringPanel.vue'
import StatusPanel from '@/components/Alerts/StatusPanel.vue'
import IncidentsPanel from '@/components/Alerts/IncidentsPanel.vue'
import ConfigurationPanel from '@/components/Alerts/ConfigurationPanel.vue'
import {
    ExclamationTriangleIcon,
    EyeIcon,
    ClipboardDocumentListIcon,
    DocumentTextIcon,
    CogIcon
} from '@heroicons/vue/24/outline'

interface Props {
    activePanel: string
    stats: {
        total_alerts: number
        active_alerts: number
        critical_alerts: number
        resolved_today: number
    }
    recentAlerts: Array<any>
    panelData: any
}

const props = defineProps<Props>()

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts'
    }
]

// Definición de paneles
const panels = [
    {
        id: 'deteccion',
        name: 'Detección Automática',
        icon: ExclamationTriangleIcon
    },
    {
        id: 'monitoreo',
        name: 'Monitoreo Tiempo Real',
        icon: EyeIcon
    },
    {
        id: 'estados',
        name: 'Estados de Alertas',
        icon: ClipboardDocumentListIcon
    },
    {
        id: 'incidencias',
        name: 'Gestión de Incidencias',
        icon: DocumentTextIcon
    },
    {
        id: 'configuracion',
        name: 'Configuración de Reglas',
        icon: CogIcon
    }
]

// Cambiar panel activo
const changePanel = (panelId: string) => {
    router.get(route('alerts.index'), { panel: panelId }, {
        preserveState: true,
        preserveScroll: true
    })
}
</script>
