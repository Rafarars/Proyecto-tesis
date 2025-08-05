<template>
    <Head title="Panel Principal" />

    <AppLayout title="Panel Principal" :breadcrumbs="breadcrumbs">
        <!-- Título del Sistema -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-6 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Sistema de Geolocalización Carúpano</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Panel de control y monitoreo de vehículos de recolección</p>
                </div>
            </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Vehículos Activos -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-emerald-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <Truck class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 truncate">
                                    Vehículos Activos
                                </dt>
                                <dd class="text-3xl font-bold text-emerald-600">
                                    {{ props.stats.vehiculosActivos }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rutas Completadas -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <Route class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 truncate">
                                    Rutas Completadas
                                </dt>
                                <dd class="text-3xl font-bold text-blue-600">
                                    {{ props.stats.rutasCompletadas }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas Activas -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-orange-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                <AlertTriangle class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 truncate">
                                    Alertas Activas
                                </dt>
                                <dd class="text-3xl font-bold text-orange-600">
                                    {{ props.stats.alertasActivas }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eficiencia Promedio -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <BarChart3 class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-600 dark:text-slate-400 truncate">
                                    Eficiencia Promedio
                                </dt>
                                <dd class="text-3xl font-bold text-purple-600">
                                    {{ props.stats.eficienciaPromedio }}%
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos directos -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Accesos Directos</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button
                        @click="navigateToMap"
                        class="flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <MapPin class="h-5 w-5" />
                        <span class="font-medium">Ver Mapa</span>
                    </button>
                    <button
                        @click="navigateToRoutes"
                        class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <Route class="h-5 w-5" />
                        <span class="font-medium">Gestionar Rutas</span>
                    </button>
                    <button
                        @click="router.visit('/alerts')"
                        class="flex items-center gap-3 p-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <AlertTriangle class="h-5 w-5" />
                        <span class="font-medium">Ver Alertas</span>
                    </button>
                    <button
                        @click="router.visit('/reportes')"
                        class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <BarChart3 class="h-5 w-5" />
                        <span class="font-medium">Reportes</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vehículos recientes -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Actividad Reciente de Vehículos</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div v-if="props.vehiculosRecientes.length === 0" class="text-center py-8">
                            <Truck class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No hay actividad reciente de vehículos</p>
                        </div>
                        <div v-else>
                            <div v-for="vehiculo in props.vehiculosRecientes" :key="vehiculo.id" class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 rounded-full" :class="{
                                            'bg-green-500': vehiculo.estado === 'activo',
                                            'bg-blue-500': vehiculo.estado === 'completado',
                                            'bg-red-500': vehiculo.estado === 'alerta',
                                            'bg-gray-500': vehiculo.estado === 'sin_datos'
                                        }"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ vehiculo.id }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ vehiculo.conductor }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ vehiculo.ruta }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                        <Clock class="h-3 w-3 mr-1" />
                                        {{ vehiculo.ultimaActualizacion }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas recientes -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Alertas Recientes</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div v-if="props.alertasRecientes.length === 0" class="text-center py-8">
                            <AlertTriangle class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No hay alertas recientes</p>
                        </div>
                        <div v-else>
                            <div v-for="alerta in props.alertasRecientes" :key="alerta.mensaje" class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-2 h-2 rounded-full" :class="{
                                        'bg-red-500': alerta.severidad === 'alta',
                                        'bg-yellow-500': alerta.severidad === 'media',
                                        'bg-blue-500': alerta.severidad === 'baja'
                                    }"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ alerta.mensaje }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ alerta.vehicle }} • {{ alerta.tiempo }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { MapPin, Truck, Route, AlertTriangle, BarChart3, Clock, CheckCircle, XCircle } from 'lucide-vue-next';

// Props del componente
interface Props {
    stats: {
        vehiculosActivos: number;
        rutasCompletadas: number;
        alertasActivas: number;
        eficienciaPromedio: number;
    };
    vehiculosRecientes: Array<{
        id: string;
        conductor: string;
        ruta: string;
        estado: string;
        ultimaActualizacion: string;
    }>;
    alertasRecientes: Array<{
        tipo: string;
        mensaje: string;
        tiempo: string;
        severidad: string;
        vehicle: string;
    }>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel Principal',
        href: '/dashboard',
    },
];

// Funciones de navegación
const navigateToMap = () => {
    router.visit('/mapa-vivo');
};

const navigateToRoutes = () => {
    router.visit('/routes');
};

const navigateToVehicles = () => {
    router.visit('/vehicles');
};

const navigateToDrivers = () => {
    router.visit('/drivers');
};
</script>
