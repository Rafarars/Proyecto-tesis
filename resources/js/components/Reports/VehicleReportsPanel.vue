<template>
    <div class="space-y-6">
        <!-- Estadísticas de vehículos por tipo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Distribución por tipo -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Vehículos por Tipo</h3>
                    <PieChart class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <DoughnutChart
                        v-if="!loading && data.vehicles_by_type"
                        :data="vehicleTypeChartData"
                        :options="doughnutOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>

            <!-- Estado de mantenimiento -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Estado de Mantenimiento</h3>
                    <Wrench class="h-5 w-5 text-gray-500" />
                </div>
                <div class="h-64">
                    <BarChart
                        v-if="!loading && data.maintenance_analysis"
                        :data="maintenanceChartData"
                        :options="barOptions"
                    />
                    <div v-else class="flex items-center justify-center h-full">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros de vehículos -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filtros</h3>
                <select
                    v-model="selectedType"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white"
                    @change="applyFilters"
                >
                    <option value="">Todos los tipos</option>
                    <option value="camion_compactador">Camión Compactador</option>
                    <option value="camion_carga">Camión de Carga</option>
                    <option value="barredora">Barredora</option>
                    <option value="contenedor_movil">Contenedor Móvil</option>
                </select>
                <select
                    v-model="selectedStatus"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white"
                    @change="applyFilters"
                >
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="mantenimiento">En Mantenimiento</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                <button
                    @click="resetFilters"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors"
                >
                    Limpiar Filtros
                </button>
            </div>
        </div>

        <!-- Tabla de vehículos -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detalle de Vehículos</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ filteredVehicles.length }} vehículos
                        </span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Vehículo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Rutas Completadas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Distancia Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alertas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Eficiencia
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Mantenimiento
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="vehicle in paginatedVehicles" :key="vehicle.id" class="hover:bg-gray-50 dark:hover:bg-slate-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center">
                                            <Truck class="h-5 w-5 text-white" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ vehicle.license_plate }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ vehicle.brand }} {{ vehicle.model }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ formatVehicleType(vehicle.type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                    vehicle.status === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                    vehicle.status === 'mantenimiento' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ formatStatus(vehicle.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ vehicle.routes_completed }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ formatDistance(vehicle.total_distance) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full',
                                    vehicle.alerts_count === 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                    vehicle.alerts_count <= 2 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ vehicle.alerts_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mr-2">
                                        {{ Math.round(vehicle.efficiency_score) }}%
                                    </div>
                                    <div class="w-16 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div 
                                            :class="[
                                                'h-2 rounded-full transition-all duration-300',
                                                vehicle.efficiency_score >= 80 ? 'bg-green-500' :
                                                vehicle.efficiency_score >= 60 ? 'bg-yellow-500' : 'bg-red-500'
                                            ]"
                                            :style="{ width: `${Math.min(100, vehicle.efficiency_score)}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                    vehicle.maintenance_status === 'al_dia' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                    vehicle.maintenance_status === 'proximo' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                ]">
                                    {{ formatMaintenanceStatus(vehicle.maintenance_status) }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div v-if="totalPages > 1" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Mostrando {{ (currentPage - 1) * itemsPerPage + 1 }} a {{ Math.min(currentPage * itemsPerPage, filteredVehicles.length) }} de {{ filteredVehicles.length }} vehículos
                    </div>
                    <div class="flex space-x-2">
                        <button
                            @click="currentPage--"
                            :disabled="currentPage === 1"
                            class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded disabled:opacity-50"
                        >
                            Anterior
                        </button>
                        <button
                            @click="currentPage++"
                            :disabled="currentPage === totalPages"
                            class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded disabled:opacity-50"
                        >
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { PieChart, Wrench, Truck } from 'lucide-vue-next';
import DoughnutChart from '@/components/Charts/DoughnutChart.vue';
import BarChart from '@/components/Charts/BarChart.vue';

interface Props {
    data: any;
    loading: boolean;
}

const props = defineProps<Props>();

// Estado de filtros
const selectedType = ref('');
const selectedStatus = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

// Datos computados para gráficos
const vehicleTypeChartData = computed(() => {
    if (!props.data.vehicles_by_type) return null;

    const labels = props.data.vehicles_by_type.map((item: any) => formatVehicleType(item.type));
    const data = props.data.vehicles_by_type.map((item: any) => item.count);

    return {
        labels,
        datasets: [
            {
                data,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                    'rgb(139, 92, 246)',
                ],
                borderWidth: 2,
            },
        ],
    };
});

const maintenanceChartData = computed(() => {
    if (!props.data.maintenance_analysis) return null;

    const labels = ['Al Día', 'Próximo', 'Vencido'];
    const data = [
        props.data.maintenance_analysis.al_dia || 0,
        props.data.maintenance_analysis.proximo || 0,
        props.data.maintenance_analysis.vencido || 0,
    ];

    return {
        labels,
        datasets: [
            {
                label: 'Vehículos',
                data,
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)',
                ],
                borderWidth: 2,
            },
        ],
    };
});

// Vehículos filtrados
const filteredVehicles = computed(() => {
    if (!props.data.vehicle_stats) return [];

    let vehicles = props.data.vehicle_stats;

    if (selectedType.value) {
        vehicles = vehicles.filter((v: any) => v.type === selectedType.value);
    }

    if (selectedStatus.value) {
        vehicles = vehicles.filter((v: any) => v.status === selectedStatus.value);
    }

    return vehicles;
});

// Paginación
const totalPages = computed(() => Math.ceil(filteredVehicles.value.length / itemsPerPage));

const paginatedVehicles = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredVehicles.value.slice(start, end);
});

// Métodos
const applyFilters = () => {
    currentPage.value = 1;
};

const resetFilters = () => {
    selectedType.value = '';
    selectedStatus.value = '';
    currentPage.value = 1;
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

const formatStatus = (status: string): string => {
    const statuses: Record<string, string> = {
        'activo': 'Activo',
        'mantenimiento': 'Mantenimiento',
        'inactivo': 'Inactivo',
    };
    return statuses[status] || status;
};

const formatMaintenanceStatus = (status: string): string => {
    const statuses: Record<string, string> = {
        'al_dia': 'Al Día',
        'proximo': 'Próximo',
        'vencido': 'Vencido',
    };
    return statuses[status] || status;
};

const formatDistance = (distance: number): string => {
    if (distance >= 1000) {
        return `${(distance / 1000).toFixed(1)} km`;
    }
    return `${distance} m`;
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
        y: {
            beginAtZero: true,
        },
    },
};
</script>
