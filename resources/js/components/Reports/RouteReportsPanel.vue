<template>
    <div class="space-y-6">
        <!-- Métricas de eficiencia -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Distancia Promedio</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ formatDistance(data.efficiency_analysis?.avg_distance || 0) }}</p>
                    </div>
                    <Route class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Duración Promedio</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ formatDuration(data.efficiency_analysis?.avg_duration || 0) }}</p>
                    </div>
                    <Clock class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Tasa de Completado</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ Math.round(data.efficiency_analysis?.completion_rate || 0) }}%</p>
                    </div>
                    <CheckCircle class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Ahorro por Optimización</p>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">${{ formatCurrency(data.efficiency_analysis?.optimization_savings || 0) }}</p>
                    </div>
                    <TrendingUp class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Rutas por estado -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rutas por Estado</h3>
                    <PieChart class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <DoughnutChart
                        v-if="!loading && data.routes_by_status"
                        :data="routeStatusChartData"
                        :options="doughnutOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>

            <!-- Eficiencia de rutas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Rutas por Eficiencia</h3>
                    <BarChart3 class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <BarChart
                        v-if="!loading && topRoutes.length > 0"
                        :data="topRoutesChartData"
                        :options="barOptions"
                        :horizontal="true"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de rutas -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalle de Rutas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ruta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distancia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duración</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Eficiencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alertas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="route in paginatedRoutes" :key="route.id" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ route.name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ route.code }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusClass(route.status)">
                                    {{ formatStatus(route.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div v-if="route.vehicle" class="text-sm">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ route.vehicle.license_plate }}</div>
                                    <div class="text-gray-500 dark:text-gray-400">{{ formatVehicleType(route.vehicle.type) }}</div>
                                </div>
                                <span v-else class="text-gray-400">Sin asignar</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ formatDistance(route.total_distance) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ formatDuration(route.actual_duration || route.estimated_duration) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ route.collection_points }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mr-2">
                                        {{ Math.round(route.efficiency_rating) }}%
                                    </div>
                                    <div class="w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div 
                                            :class="getEfficiencyColor(route.efficiency_rating)"
                                            :style="{ width: `${Math.min(100, route.efficiency_rating)}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getAlertClass(route.alerts_count)">
                                    {{ route.alerts_count }}
                                </span>
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
import { Route, Clock, CheckCircle, TrendingUp, PieChart, BarChart3 } from 'lucide-vue-next';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';
import BarChart from '@/components/Charts/BarChart.vue';

interface Props {
    data: any;
    loading: boolean;
}

const props = defineProps<Props>();

const currentPage = ref(1);
const itemsPerPage = 10;

// Datos computados
const routeStatusChartData = computed(() => {
    if (!props.data.routes_by_status) return null;

    const labels = props.data.routes_by_status.map((item: any) => formatStatus(item.status));
    const data = props.data.routes_by_status.map((item: any) => item.count);

    return {
        labels,
        datasets: [{
            data,
            backgroundColor: [
                'rgba(34, 197, 94, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
            ],
            borderColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(245, 158, 11)',
                'rgb(239, 68, 68)',
            ],
            borderWidth: 2,
        }],
    };
});

const topRoutes = computed(() => {
    if (!props.data.route_stats) return [];
    return props.data.route_stats
        .filter((route: any) => route.efficiency_rating > 0)
        .sort((a: any, b: any) => b.efficiency_rating - a.efficiency_rating)
        .slice(0, 5);
});

const topRoutesChartData = computed(() => {
    if (topRoutes.value.length === 0) return null;

    return {
        labels: topRoutes.value.map((route: any) => route.name),
        datasets: [{
            label: 'Eficiencia (%)',
            data: topRoutes.value.map((route: any) => route.efficiency_rating),
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 2,
        }],
    };
});

const paginatedRoutes = computed(() => {
    if (!props.data.route_stats) return [];
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return props.data.route_stats.slice(start, end);
});

// Métodos de formato
const formatDistance = (distance: number): string => {
    if (distance >= 1000) {
        return `${(distance / 1000).toFixed(1)} km`;
    }
    return `${distance} m`;
};

const formatDuration = (minutes: number): string => {
    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return `${hours}h ${mins}m`;
    }
    return `${minutes}m`;
};

const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('es-VE', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

const formatStatus = (status: string): string => {
    const statuses: Record<string, string> = {
        'completada': 'Completada',
        'en_progreso': 'En Progreso',
        'planificada': 'Planificada',
        'cancelada': 'Cancelada',
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

const getStatusClass = (status: string): string => {
    const classes: Record<string, string> = {
        'completada': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'en_progreso': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'planificada': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'cancelada': 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };
    return classes[status] || 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
};

const getEfficiencyColor = (efficiency: number): string => {
    if (efficiency >= 80) return 'bg-green-500 h-2 rounded-full transition-all duration-300';
    if (efficiency >= 60) return 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
    return 'bg-red-500 h-2 rounded-full transition-all duration-300';
};

const getAlertClass = (count: number): string => {
    if (count === 0) return 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
    if (count <= 2) return 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
    return 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
};

// Opciones para gráficos
const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
        },
    },
};

const barOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        x: {
            beginAtZero: true,
            max: 100,
        },
    },
};
</script>
