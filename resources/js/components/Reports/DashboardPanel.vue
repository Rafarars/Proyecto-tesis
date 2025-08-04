<template>
    <div class="space-y-6">
        <!-- KPIs Principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Utilización de Flota -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Utilización de Flota</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ Math.round(data.kpis?.fleet_utilization || 0) }}%</p>
                    </div>
                    <div class="p-2 bg-blue-200 dark:bg-blue-800 rounded-lg">
                        <Truck class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>

            <!-- Eficiencia de Rutas -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">Eficiencia de Rutas</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ Math.round(data.kpis?.route_efficiency || 0) }}%</p>
                    </div>
                    <div class="p-2 bg-green-200 dark:bg-green-800 rounded-lg">
                        <Route class="h-6 w-6 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            <!-- Tasa de Resolución -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Resolución de Alertas</p>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ Math.round(data.kpis?.alert_resolution_rate || 0) }}%</p>
                    </div>
                    <div class="p-2 bg-orange-200 dark:bg-orange-800 rounded-lg">
                        <AlertTriangle class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                    </div>
                </div>
            </div>

            <!-- Ahorro en Costos -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Ahorro Operativo</p>
                        <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">${{ formatCurrency(data.kpis?.operational_cost_savings || 0) }}</p>
                    </div>
                    <div class="p-2 bg-purple-200 dark:bg-purple-800 rounded-lg">
                        <TrendingUp class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos principales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tendencias Diarias -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tendencias Diarias</h3>
                    <Activity class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <LineChart
                        v-if="!loading && dailyTrendsChartData"
                        :data="dailyTrendsChartData"
                        :options="chartOptions"
                    />
                    <div v-else-if="loading" class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                    <div v-else class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <Activity class="h-12 w-12 mx-auto mb-2 opacity-50" />
                            <p>No hay datos de tendencias disponibles</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribución de Alertas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Distribución de Alertas</h3>
                    <PieChart class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <DoughnutChart
                        v-if="!loading && alertDistributionChartData"
                        :data="alertDistributionChartData"
                        :options="doughnutOptions"
                    />
                    <div v-else-if="loading" class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                    <div v-else class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <PieChart class="h-12 w-12 mx-auto mb-2 opacity-50" />
                            <p>No hay datos de alertas disponibles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Vehículos -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Vehículos por Rendimiento</h3>
                <Trophy class="h-5 w-5 text-gray-500" />
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Posición
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Vehículo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Rutas Completadas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Puntuación
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(vehicle, index) in data.top_vehicles" :key="vehicle.license_plate" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div :class="[
                                            'h-8 w-8 rounded-full flex items-center justify-center text-white font-bold text-sm',
                                            index === 0 ? 'bg-yellow-500' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-600' : 'bg-gray-300'
                                        ]">
                                            {{ index + 1 }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ vehicle.license_plate }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ vehicle.type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ vehicle.routes_completed }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mr-2">
                                        {{ Math.round(vehicle.efficiency_score) }}%
                                    </div>
                                    <div class="w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div 
                                            class="bg-emerald-500 h-2 rounded-full transition-all duration-300"
                                            :style="{ width: `${Math.min(100, vehicle.efficiency_score)}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { 
    Truck, 
    Route, 
    AlertTriangle, 
    TrendingUp, 
    Activity, 
    PieChart, 
    Trophy 
} from 'lucide-vue-next';
import LineChart from '@/components/Charts/LineChart.vue';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';

interface Props {
    data: any;
    loading: boolean;
}

const props = defineProps<Props>();

// Formatear moneda
const formatCurrency = (value: number): string => {
    return new Intl.NumberFormat('es-VE', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

// Datos para gráfico de tendencias diarias
const dailyTrendsChartData = computed(() => {
    if (!props.data?.daily_trends || !Array.isArray(props.data.daily_trends) || props.data.daily_trends.length === 0) {
        return null;
    }

    const labels = props.data.daily_trends.map((item: any) => {
        const date = new Date(item.date);
        return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
    });

    return {
        labels,
        datasets: [
            {
                label: 'Rutas Completadas',
                data: props.data.daily_trends.map((item: any) => item.routes_completed),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
            },
            {
                label: 'Alertas Generadas',
                data: props.data.daily_trends.map((item: any) => item.alerts_generated),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
            },
            {
                label: 'Vehículos Activos',
                data: props.data.daily_trends.map((item: any) => item.vehicles_active),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
            },
        ],
    };
});

// Datos para gráfico de distribución de alertas
const alertDistributionChartData = computed(() => {
    if (!props.data?.alert_distribution || typeof props.data.alert_distribution !== 'object') {
        return null;
    }

    const labels = Object.keys(props.data.alert_distribution);
    const data = Object.values(props.data.alert_distribution);

    return {
        labels: labels.map(label => {
            const translations: Record<string, string> = {
                'desvio_ruta': 'Desvío de Ruta',
                'parada_prolongada': 'Parada Prolongada',
                'perdida_senal': 'Pérdida de Señal',
                'mantenimiento': 'Mantenimiento',
                'combustible_bajo': 'Combustible Bajo',
            };
            return translations[label] || label;
        }),
        datasets: [
            {
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
            },
        ],
    };
});

// Opciones para gráficos
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top' as const,
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
