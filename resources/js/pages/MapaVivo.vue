<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Mapa en Vivo" />

        <div class="space-y-6">
            <!-- Header de la página -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Mapa en Vivo
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Seguimiento en tiempo real de la flota de recolección de desechos sólidos
                        </p>
                    </div>
                    
                    <!-- Indicadores de estado -->
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ stats.activeVehicles }}</div>
                            <div class="text-sm text-gray-500">Vehículos Activos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ stats.totalRoutes }}</div>
                            <div class="text-sm text-gray-500">Rutas en Progreso</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ stats.alerts }}</div>
                            <div class="text-sm text-gray-500">Alertas Activas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Componente principal del mapa -->
            <LiveMap />

            <!-- Panel de información adicional -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Vehículos en línea -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Vehículos en Línea
                    </h3>
                    <div class="space-y-3">
                        <div 
                            v-for="vehicle in onlineVehicles" 
                            :key="vehicle.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                        >
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ vehicle.license_plate }}</div>
                                    <div class="text-sm text-gray-500">{{ vehicle.type }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">{{ vehicle.speed }} km/h</div>
                                <div class="text-xs text-gray-500">{{ formatTime(vehicle.updated_at) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas recientes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Alertas Recientes
                    </h3>
                    <div class="space-y-3">
                        <div 
                            v-for="alert in recentAlerts" 
                            :key="alert.id"
                            class="flex items-start p-3 bg-orange-50 border border-orange-200 rounded-lg"
                        >
                            <div class="flex-shrink-0">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ alert.message }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ alert.vehicle }} - {{ formatTime(alert.timestamp) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del día -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Estadísticas del Día
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Distancia Total</span>
                            <span class="font-semibold text-gray-900">{{ dailyStats.totalDistance }} km</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tiempo Activo</span>
                            <span class="font-semibold text-gray-900">{{ dailyStats.activeTime }} hrs</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Rutas Completadas</span>
                            <span class="font-semibold text-gray-900">{{ dailyStats.completedRoutes }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Eficiencia</span>
                            <span class="font-semibold text-green-600">{{ dailyStats.efficiency }}%</span>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Combustible Estimado</span>
                                <span class="font-semibold text-gray-900">{{ dailyStats.fuelUsed }} L</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import LiveMap from '@/components/Map/LiveMap.vue'
import { type BreadcrumbItem } from '@/types'

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Mapa en Vivo',
        href: '/mapa-vivo',
        current: true
    }
]

// Datos reactivos
const stats = ref({
    activeVehicles: 4,
    totalRoutes: 3,
    alerts: 2
})

const onlineVehicles = ref([])

const recentAlerts = ref([])

const dailyStats = ref({
    totalDistance: '127.5',
    activeTime: '6.2',
    completedRoutes: '3',
    efficiency: '87',
    fuelUsed: '45.2'
})

// Métodos
const formatTime = (timestamp: string) => {
    return new Date(timestamp).toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Funciones para cargar datos reales
const loadVehicleData = async () => {
    try {
        const response = await fetch('/api/live-vehicles')
        const data = await response.json()

        if (data.vehicles) {
            onlineVehicles.value = data.vehicles.map(vehicle => ({
                id: vehicle.id,
                license_plate: vehicle.license_plate,
                type: vehicle.type,
                speed: vehicle.current_speed || 0,
                updated_at: vehicle.location_updated_at || new Date().toISOString()
            }))

            stats.value.activeVehicles = data.active_count || 0
        }
    } catch (error) {
        console.error('Error loading vehicle data:', error)
    }
}

const loadAlerts = async () => {
    try {
        const response = await fetch('/alerts')
        const data = await response.json()

        if (data.alerts) {
            recentAlerts.value = data.alerts.slice(0, 5).map(alert => ({
                id: alert.id,
                message: alert.message,
                vehicle: alert.vehicle_code || 'N/A',
                timestamp: alert.created_at
            }))

            stats.value.alerts = data.alerts.filter(alert => alert.status === 'activa').length
        }
    } catch (error) {
        console.error('Error loading alerts:', error)
    }
}

// Lifecycle
onMounted(() => {
    // Cargar datos iniciales
    loadVehicleData()
    loadAlerts()

    // Actualizar datos cada 30 segundos
    setInterval(() => {
        loadVehicleData()
        loadAlerts()
    }, 30000)
})
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>
