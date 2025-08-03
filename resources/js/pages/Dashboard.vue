<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { MapPin, Truck, Route, AlertTriangle, BarChart3, Clock, CheckCircle, XCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Panel Principal',
        href: '/dashboard',
    },
];

// Datos simulados para el dashboard
const stats = {
    vehiculosActivos: 12,
    rutasCompletadas: 8,
    alertasActivas: 3,
    eficienciaPromedio: 87
};

// Funciones de navegación
const navigateToMap = () => {
    router.visit('/mapa-vivo');
};

const vehiculosRecientes = [
    { id: 'V001', conductor: 'Juan Pérez', ruta: 'Zona Centro', estado: 'activo', ultimaActualizacion: '10:30 AM' },
    { id: 'V002', conductor: 'María González', ruta: 'Zona Norte', estado: 'completado', ultimaActualizacion: '10:15 AM' },
    { id: 'V003', conductor: 'Carlos Rodríguez', ruta: 'Zona Sur', estado: 'alerta', ultimaActualizacion: '10:45 AM' },
];

const alertasRecientes = [
    { tipo: 'desvio', mensaje: 'Vehículo V003 fuera de ruta', tiempo: '5 min ago', severidad: 'alta' },
    { tipo: 'mantenimiento', mensaje: 'Vehículo V001 requiere mantenimiento', tiempo: '15 min ago', severidad: 'media' },
    { tipo: 'combustible', mensaje: 'Nivel bajo de combustible V002', tiempo: '30 min ago', severidad: 'baja' },
];
</script>

<template>
    <Head title="Panel Principal - Sistema de Geolocalización Carúpano" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6 overflow-x-auto bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800">

            <!-- Bienvenida -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-emerald-200 dark:border-emerald-800">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                        <MapPin class="h-8 w-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">Sistema de Geolocalización Carúpano</h1>
                        <p class="text-slate-600 dark:text-slate-400">Monitoreo y optimización de rutas de recolección de desechos</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Vehículos Activos</p>
                            <p class="text-3xl font-bold text-emerald-600">{{ stats.vehiculosActivos }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
                            <Truck class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Rutas Completadas</p>
                            <p class="text-3xl font-bold text-blue-600">{{ stats.rutasCompletadas }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <Route class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Alertas Activas</p>
                            <p class="text-3xl font-bold text-amber-600">{{ stats.alertasActivas }}</p>
                        </div>
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-lg">
                            <AlertTriangle class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Eficiencia</p>
                            <p class="text-3xl font-bold text-green-600">{{ stats.eficienciaPromedio }}%</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <BarChart3 class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal en dos columnas -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Estado de Vehículos -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <Truck class="h-5 w-5 text-emerald-600" />
                            Estado de Vehículos
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div v-for="vehiculo in vehiculosRecientes" :key="vehiculo.id" class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2">
                                        <CheckCircle v-if="vehiculo.estado === 'completado'" class="h-5 w-5 text-green-500" />
                                        <Clock v-else-if="vehiculo.estado === 'activo'" class="h-5 w-5 text-blue-500" />
                                        <XCircle v-else class="h-5 w-5 text-red-500" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800 dark:text-slate-200">{{ vehiculo.id }} - {{ vehiculo.conductor }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ vehiculo.ruta }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ vehiculo.ultimaActualizacion }}</p>
                                    <span :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': vehiculo.estado === 'completado',
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': vehiculo.estado === 'activo',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': vehiculo.estado === 'alerta'
                                    }" class="px-2 py-1 rounded-full text-xs font-medium">
                                        {{ vehiculo.estado === 'completado' ? 'Completado' : vehiculo.estado === 'activo' ? 'En Ruta' : 'Alerta' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas Recientes -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <AlertTriangle class="h-5 w-5 text-amber-600" />
                            Alertas Recientes
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div v-for="alerta in alertasRecientes" :key="alerta.mensaje" class="flex items-start gap-3 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div :class="{
                                    'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400': alerta.severidad === 'alta',
                                    'bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-400': alerta.severidad === 'media',
                                    'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-400': alerta.severidad === 'baja'
                                }" class="p-2 rounded-lg">
                                    <AlertTriangle class="h-4 w-4" />
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800 dark:text-slate-200">{{ alerta.mensaje }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ alerta.tiempo }}</p>
                                </div>
                                <span :class="{
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': alerta.severidad === 'alta',
                                    'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200': alerta.severidad === 'media',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': alerta.severidad === 'baja'
                                }" class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ alerta.severidad === 'alta' ? 'Alta' : alerta.severidad === 'media' ? 'Media' : 'Baja' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos rápidos -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">Accesos Rápidos</h3>
                <div class="grid gap-4 md:grid-cols-4">
                    <button
                        @click="navigateToMap"
                        class="flex items-center gap-3 p-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <MapPin class="h-5 w-5" />
                        <span class="font-medium">Ver Mapa</span>
                    </button>
                    <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <Route class="h-5 w-5" />
                        <span class="font-medium">Crear Ruta</span>
                    </button>
                    <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <AlertTriangle class="h-5 w-5" />
                        <span class="font-medium">Ver Alertas</span>
                    </button>
                    <button class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <BarChart3 class="h-5 w-5" />
                        <span class="font-medium">Reportes</span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
