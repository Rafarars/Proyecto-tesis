<template>
    <div class="space-y-6">
        <!-- Comparativa con período anterior -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Comparativa con Período Anterior</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ data.previous_period_comparison?.current_period?.completed_routes || 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Rutas Completadas</div>
                    <div :class="getChangeClass(data.previous_period_comparison?.changes?.routes_change || 0)">
                        <TrendingUp v-if="(data.previous_period_comparison?.changes?.routes_change || 0) > 0" class="h-4 w-4 inline mr-1" />
                        <TrendingDown v-else class="h-4 w-4 inline mr-1" />
                        {{ Math.abs(data.previous_period_comparison?.changes?.routes_change || 0).toFixed(1) }}%
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ data.previous_period_comparison?.current_period?.total_alerts || 0 }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Alertas</div>
                    <div :class="getChangeClass(-(data.previous_period_comparison?.changes?.alerts_change || 0))">
                        <TrendingUp v-if="(data.previous_period_comparison?.changes?.alerts_change || 0) < 0" class="h-4 w-4 inline mr-1" />
                        <TrendingDown v-else class="h-4 w-4 inline mr-1" />
                        {{ Math.abs(data.previous_period_comparison?.changes?.alerts_change || 0).toFixed(1) }}%
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ Math.round(data.previous_period_comparison?.current_period?.efficiency_rate || 0) }}%</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Eficiencia General</div>
                    <div :class="getChangeClass(data.previous_period_comparison?.changes?.efficiency_change || 0)">
                        <TrendingUp v-if="(data.previous_period_comparison?.changes?.efficiency_change || 0) > 0" class="h-4 w-4 inline mr-1" />
                        <TrendingDown v-else class="h-4 w-4 inline mr-1" />
                        {{ Math.abs(data.previous_period_comparison?.changes?.efficiency_change || 0).toFixed(1) }}%
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos temporales -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Análisis por horas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actividad por Horas</h3>
                    <Clock class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <LineChart
                        v-if="!loading && data.hourly_analysis"
                        :data="hourlyChartData"
                        :options="lineOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>

            <!-- Análisis semanal -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actividad por Días de la Semana</h3>
                    <Calendar class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <BarChart
                        v-if="!loading && data.weekly_analysis"
                        :data="weeklyChartData"
                        :options="barOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendencias mensuales -->
        <div v-if="data.monthly_trends && data.monthly_trends.length > 1" class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tendencias Mensuales</h3>
                <TrendingUp class="h-5 w-5 text-gray-500" />
            </div>
            <div class="h-80">
                <LineChart
                    v-if="!loading"
                    :data="monthlyTrendsChartData"
                    :options="lineOptions"
                />
                <div v-else class="flex items-center justify-center h-full">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                </div>
            </div>
        </div>

        <!-- Tabla de análisis detallado -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Análisis Detallado por Períodos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rutas Completadas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Alertas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Eficiencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tendencia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="trend in data.monthly_trends" :key="trend.month" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ trend.month_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ trend.routes_completed }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ trend.alerts_total }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mr-2">
                                        {{ Math.round(trend.efficiency_rate) }}%
                                    </div>
                                    <div class="w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div 
                                            :class="getEfficiencyColor(trend.efficiency_rate)"
                                            :style="{ width: `${Math.min(100, trend.efficiency_rate)}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <TrendingUp v-if="trend.efficiency_rate >= 75" class="h-4 w-4 text-green-500 mr-1" />
                                    <Minus v-else-if="trend.efficiency_rate >= 50" class="h-4 w-4 text-yellow-500 mr-1" />
                                    <TrendingDown v-else class="h-4 w-4 text-red-500 mr-1" />
                                    <span :class="[
                                        'text-sm font-medium',
                                        trend.efficiency_rate >= 75 ? 'text-green-600' :
                                        trend.efficiency_rate >= 50 ? 'text-yellow-600' : 'text-red-600'
                                    ]">
                                        {{ trend.efficiency_rate >= 75 ? 'Excelente' : trend.efficiency_rate >= 50 ? 'Regular' : 'Bajo' }}
                                    </span>
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
import { Clock, Calendar, TrendingUp, TrendingDown, Minus } from 'lucide-vue-next';
import LineChart from '@/components/Charts/LineChart.vue';
import BarChart from '@/components/Charts/BarChart.vue';

interface Props {
    data: any;
    loading: boolean;
}

const props = defineProps<Props>();

// Datos computados para gráficos
const hourlyChartData = computed(() => {
    if (!props.data.hourly_analysis) return null;

    const labels = props.data.hourly_analysis.map((item: any) => `${item.hour}:00`);

    return {
        labels,
        datasets: [
            {
                label: 'Alertas',
                data: props.data.hourly_analysis.map((item: any) => item.alerts),
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
            },
            {
                label: 'Rutas Iniciadas',
                data: props.data.hourly_analysis.map((item: any) => item.routes_started),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
            },
        ],
    };
});

const weeklyChartData = computed(() => {
    if (!props.data.weekly_analysis) return null;

    const labels = props.data.weekly_analysis.map((item: any) => item.day);

    return {
        labels,
        datasets: [
            {
                label: 'Alertas',
                data: props.data.weekly_analysis.map((item: any) => item.alerts),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 2,
            },
            {
                label: 'Rutas Completadas',
                data: props.data.weekly_analysis.map((item: any) => item.routes_completed),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 2,
            },
        ],
    };
});

const monthlyTrendsChartData = computed(() => {
    if (!props.data.monthly_trends) return null;

    const labels = props.data.monthly_trends.map((item: any) => item.month_name);

    return {
        labels,
        datasets: [
            {
                label: 'Rutas Completadas',
                data: props.data.monthly_trends.map((item: any) => item.routes_completed),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                yAxisID: 'y',
            },
            {
                label: 'Eficiencia (%)',
                data: props.data.monthly_trends.map((item: any) => item.efficiency_rate),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                yAxisID: 'y1',
            },
        ],
    };
});

// Métodos de formato
const getChangeClass = (change: number): string => {
    if (change > 0) {
        return 'text-sm font-medium text-green-600 dark:text-green-400 flex items-center justify-center';
    } else if (change < 0) {
        return 'text-sm font-medium text-red-600 dark:text-red-400 flex items-center justify-center';
    }
    return 'text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center justify-center';
};

const getEfficiencyColor = (efficiency: number): string => {
    if (efficiency >= 80) return 'bg-green-500 h-2 rounded-full transition-all duration-300';
    if (efficiency >= 60) return 'bg-yellow-500 h-2 rounded-full transition-all duration-300';
    return 'bg-red-500 h-2 rounded-full transition-all duration-300';
};

// Opciones para gráficos
const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top' as const,
        },
    },
    scales: {
        y: {
            type: 'linear' as const,
            display: true,
            position: 'left' as const,
            beginAtZero: true,
        },
        y1: {
            type: 'linear' as const,
            display: true,
            position: 'right' as const,
            beginAtZero: true,
            max: 100,
            grid: {
                drawOnChartArea: false,
            },
        },
    },
};

const barOptions = {
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
</script>
