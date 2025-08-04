<template>
    <div class="space-y-6">
        <!-- Métricas de alertas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Tiempo Respuesta Promedio</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ Math.round(data.response_analysis?.avg_response_time || 0) }}m</p>
                    </div>
                    <Clock class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Tiempo Resolución Promedio</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ Math.round(data.response_analysis?.avg_resolution_time || 0) }}m</p>
                    </div>
                    <CheckCircle class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Tasa de Resolución</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ Math.round(data.response_analysis?.resolution_rate || 0) }}%</p>
                    </div>
                    <TrendingUp class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-6 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600 dark:text-red-400">Alertas Críticas</p>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ data.response_analysis?.critical_alerts || 0 }}</p>
                    </div>
                    <AlertTriangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Alertas por tipo -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alertas por Tipo</h3>
                    <BarChart3 class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <BarChart
                        v-if="!loading && data.alerts_by_type"
                        :data="alertTypeChartData"
                        :options="barOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>

            <!-- Alertas por severidad -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución por Severidad</h3>
                    <PieChart class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <DoughnutChart
                        v-if="!loading && data.alerts_by_severity"
                        :data="alertSeverityChartData"
                        :options="doughnutOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de alertas -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Historial de Alertas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Severidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detectada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tiempo Respuesta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tiempo Resolución</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="alert in paginatedAlerts" :key="alert.id" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ formatAlertType(alert.type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getSeverityClass(alert.severity)">
                                    {{ formatSeverity(alert.severity) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusClass(alert.status)">
                                    {{ formatStatus(alert.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div v-if="alert.vehicle" class="text-sm">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ alert.vehicle.license_plate }}</div>
                                    <div class="text-gray-500 dark:text-gray-400">{{ formatVehicleType(alert.vehicle.type) }}</div>
                                </div>
                                <span v-else class="text-gray-400">N/A</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ alert.detected_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ alert.response_time ? `${alert.response_time}m` : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ alert.resolution_time ? `${alert.resolution_time}m` : 'N/A' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';
import { Clock, CheckCircle, TrendingUp, AlertTriangle, BarChart3, PieChart } from 'lucide-vue-next';
import BarChart from '@/components/Charts/BarChart.vue';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';

interface Props {
    data: any;
    loading: boolean;
}

const props = defineProps<Props>();

const currentPage = ref(1);
const itemsPerPage = 10;

// Datos computados para gráficos
const alertTypeChartData = computed(() => {
    if (!props.data.alerts_by_type) return null;

    const labels = props.data.alerts_by_type.map((item: any) => formatAlertType(item.type));
    const data = props.data.alerts_by_type.map((item: any) => item.count);

    return {
        labels,
        datasets: [{
            label: 'Cantidad',
            data,
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(107, 114, 128, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
            ],
            borderColor: [
                'rgb(239, 68, 68)',
                'rgb(245, 158, 11)',
                'rgb(107, 114, 128)',
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
            ],
            borderWidth: 2,
        }],
    };
});

const alertSeverityChartData = computed(() => {
    if (!props.data.alerts_by_severity) return null;

    const labels = props.data.alerts_by_severity.map((item: any) => formatSeverity(item.severity));
    const data = props.data.alerts_by_severity.map((item: any) => item.count);

    return {
        labels,
        datasets: [{
            data,
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(107, 114, 128, 0.8)',
            ],
            borderColor: [
                'rgb(239, 68, 68)',
                'rgb(245, 158, 11)',
                'rgb(59, 130, 246)',
                'rgb(107, 114, 128)',
            ],
            borderWidth: 2,
        }],
    };
});

const paginatedAlerts = computed(() => {
    if (!props.data.alert_stats) return [];
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.data.alert_stats.slice(start, end);
});

// Métodos de formato
const formatAlertType = (type: string): string => {
    const types: Record<string, string> = {
        'desvio_ruta': 'Desvío de Ruta',
        'parada_prolongada': 'Parada Prolongada',
        'perdida_senal': 'Pérdida de Señal',
        'mantenimiento': 'Mantenimiento',
        'combustible_bajo': 'Combustible Bajo',
    };
    return types[type] || type;
};

const formatSeverity = (severity: string): string => {
    const severities: Record<string, string> = {
        'critica': 'Crítica',
        'alta': 'Alta',
        'media': 'Media',
        'baja': 'Baja',
    };
    return severities[severity] || severity;
};

const formatStatus = (status: string): string => {
    const statuses: Record<string, string> = {
        'abierta': 'Abierta',
        'en_atencion': 'En Atención',
        'resuelta': 'Resuelta',
        'cerrada': 'Cerrada',
    };
    return statuses[status] || status;
};

const formatVehicleType = (type: string): string => {
    const types: Record<string, string> = {
        'camion_compactador': 'Camión Compactador',
        'camion_carga': 'Camión de Carga',
        'barredora': 'Barredora',
        'contenedor_movil': 'Contenedor Móvil',
    };
    return types[type] || type;
};

const getSeverityClass = (severity: string): string => {
    const classes: Record<string, string> = {
        'critica': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'alta': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        'media': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'baja': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    };
    return classes[severity] || 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const getStatusClass = (status: string): string => {
    const classes: Record<string, string> = {
        'abierta': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'en_atencion': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'resuelta': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'cerrada': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
    };
    return classes[status] || 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

// Opciones para gráficos
const barOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
        },
    },
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
        },
    },
};
</script>
