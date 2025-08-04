<template>
    <AppLayout title="Reportes y Análisis" :breadcrumbs="breadcrumbs">
        <Head title="Reportes y Análisis" />

        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <BarChart3 class="h-8 w-8 text-white" />
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reportes y Análisis</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Análisis de rendimiento y métricas del sistema de geolocalización</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Filtros de fecha -->
                    <div class="flex items-center space-x-2 bg-gray-50 dark:bg-slate-700 rounded-lg p-2">
                        <Calendar class="h-4 w-4 text-gray-500" />
                        <input
                            v-model="dateFrom"
                            type="date"
                            class="bg-transparent border-none text-sm focus:ring-0 focus:outline-none text-gray-700 dark:text-gray-300"
                            @change="updateDateRange"
                        />
                        <span class="text-gray-400">-</span>
                        <input
                            v-model="dateTo"
                            type="date"
                            class="bg-transparent border-none text-sm focus:ring-0 focus:outline-none text-gray-700 dark:text-gray-300"
                            @change="updateDateRange"
                        />
                    </div>
                    <!-- Botón de exportar -->
                    <div class="relative">
                        <button
                            @click="showExportMenu = !showExportMenu"
                            class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <Download class="w-5 h-5 mr-2" />
                            Exportar
                        </button>
                        <!-- Menú de exportación -->
                        <div v-if="showExportMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-10">
                            <button
                                @click="exportReport('pdf')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-t-lg"
                            >
                                <FileText class="w-4 h-4 inline mr-2" />
                                Exportar a PDF
                            </button>
                            <button
                                @click="exportReport('excel')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-b-lg"
                            >
                                <FileSpreadsheet class="w-4 h-4 inline mr-2" />
                                Exportar a Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Vehículos -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-blue-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Vehículos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ generalStats.total_vehicles }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl shadow-md">
                        <Truck class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>

            <!-- Rutas Completadas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-green-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rutas Completadas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ generalStats.completed_routes }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl shadow-md">
                        <Route class="h-8 w-8 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            <!-- Alertas Resueltas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-orange-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Alertas Resueltas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ generalStats.resolved_alerts }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl shadow-md">
                        <AlertTriangle class="h-8 w-8 text-orange-600 dark:text-orange-400" />
                    </div>
                </div>
            </div>

            <!-- Eficiencia General -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-purple-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Eficiencia General</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ Math.round(generalStats.efficiency_rate) }}%</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl shadow-md">
                        <TrendingUp class="h-8 w-8 text-purple-600 dark:text-purple-400" />
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
                            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200',
                            activePanel === panel.id
                                ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
                        ]"
                    >
                        <component :is="panel.icon" class="w-5 h-5 inline mr-2" />
                        {{ panel.name }}
                    </button>
                </nav>
            </div>

            <!-- Contenido del panel activo -->
            <div class="p-6">
                <!-- Dashboard General -->
                <div v-if="activePanel === 'dashboard'" class="space-y-6">
                    <DashboardPanel :data="panelData" :loading="loading" />
                </div>

                <!-- Reportes de Vehículos -->
                <div v-else-if="activePanel === 'vehicles'" class="space-y-6">
                    <VehicleReportsPanel :data="panelData" :loading="loading" />
                </div>

                <!-- Reportes de Rutas -->
                <div v-else-if="activePanel === 'routes'" class="space-y-6">
                    <RouteReportsPanel :data="panelData" :loading="loading" />
                </div>

                <!-- Reportes de Alertas -->
                <div v-else-if="activePanel === 'alerts'" class="space-y-6">
                    <AlertReportsPanel :data="panelData" :loading="loading" />
                </div>

                <!-- Análisis Temporal -->
                <div v-else-if="activePanel === 'temporal'" class="space-y-6">
                    <TemporalAnalysisPanel :data="panelData" :loading="loading" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import {
    BarChart3,
    Calendar,
    Download,
    FileText,
    FileSpreadsheet,
    Truck,
    Route,
    AlertTriangle,
    TrendingUp,
    Activity,
    Clock,
    MapPin,
    PieChart
} from 'lucide-vue-next';

// Importar componentes de paneles (se crearán después)
import DashboardPanel from '@/components/Reports/DashboardPanel.vue';
import VehicleReportsPanel from '@/components/Reports/VehicleReportsPanel.vue';
import RouteReportsPanel from '@/components/Reports/RouteReportsPanel.vue';
import AlertReportsPanel from '@/components/Reports/AlertReportsPanel.vue';
import TemporalAnalysisPanel from '@/components/Reports/TemporalAnalysisPanel.vue';

interface Props {
    activePanel: string;
    dateFrom: string;
    dateTo: string;
    generalStats: any;
    panelData: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel Principal',
        href: '/dashboard',
    },
    {
        title: 'Reportes y Análisis',
        href: '/reportes',
    },
];

// Estado reactivo
const activePanel = ref(props.activePanel);
const dateFrom = ref(props.dateFrom);
const dateTo = ref(props.dateTo);
const generalStats = ref(props.generalStats);
const panelData = ref(props.panelData);
const loading = ref(false);
const showExportMenu = ref(false);

// Definición de paneles
const panels = [
    {
        id: 'dashboard',
        name: 'Dashboard General',
        icon: Activity,
    },
    {
        id: 'vehicles',
        name: 'Vehículos',
        icon: Truck,
    },
    {
        id: 'routes',
        name: 'Rutas',
        icon: Route,
    },
    {
        id: 'alerts',
        name: 'Alertas',
        icon: AlertTriangle,
    },
    {
        id: 'temporal',
        name: 'Análisis Temporal',
        icon: Clock,
    },
];

// Métodos
const changePanel = (panelId: string) => {
    if (panelId === activePanel.value) return;
    
    loading.value = true;
    router.visit(route('reports.index'), {
        data: {
            panel: panelId,
            date_from: dateFrom.value,
            date_to: dateTo.value,
        },
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        }
    });
};

const updateDateRange = () => {
    loading.value = true;
    router.visit(route('reports.index'), {
        data: {
            panel: activePanel.value,
            date_from: dateFrom.value,
            date_to: dateTo.value,
        },
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        }
    });
};

const exportReport = (format: 'pdf' | 'excel') => {
    showExportMenu.value = false;
    
    const url = route('reports.export', { type: activePanel.value });
    const params = new URLSearchParams({
        format,
        panel: activePanel.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
    });
    
    window.open(`${url}?${params.toString()}`, '_blank');
};

// Watchers
watch(() => props.activePanel, (newPanel) => {
    activePanel.value = newPanel;
});

watch(() => props.generalStats, (newStats) => {
    generalStats.value = newStats;
});

watch(() => props.panelData, (newData) => {
    panelData.value = newData;
});

// Cerrar menú de exportación al hacer clic fuera
onMounted(() => {
    document.addEventListener('click', (e) => {
        if (!e.target?.closest('.relative')) {
            showExportMenu.value = false;
        }
    });
});
</script>
