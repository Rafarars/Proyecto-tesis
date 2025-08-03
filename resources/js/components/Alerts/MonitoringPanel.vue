<template>
    <div class="space-y-6">
        <!-- Título del panel -->
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monitoreo en Tiempo Real</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Vista consolidada de alertas activas y ubicaciones</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Actualización en vivo</span>
                </div>
                <Link
                    :href="route('mapa.vivo')"
                    class="inline-flex items-center px-3 py-2 border border-emerald-300 dark:border-emerald-600 text-sm leading-4 font-medium rounded-md text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Ver Mapa en Vivo
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Minimapa con alertas -->
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Mapa de Alertas</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Vehículos con alertas activas</p>
                </div>
                
                <div class="relative">
                    <div
                        ref="mapContainer"
                        id="monitoring-map"
                        class="w-full h-80 bg-gray-100 dark:bg-gray-700"
                    ></div>
                    
                    <!-- Controles del mapa -->
                    <div class="absolute top-4 right-4 bg-white dark:bg-slate-800 rounded-lg shadow-lg p-3 space-y-2 z-[1000] border border-gray-200 dark:border-gray-600">
                        <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Leyenda</div>
                        <div class="flex flex-col space-y-1 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                Alerta crítica
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                                Alerta alta
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                Alerta media
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                Alerta baja
                            </div>
                        </div>
                    </div>

                    <!-- Indicador de carga del mapa -->
                    <div v-if="mapLoading" class="absolute inset-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto mb-2"></div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Cargando mapa...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de alertas activas -->
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Alertas Activas</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ recentAlerts.length }} alertas requieren atención</p>
                </div>
                
                <div class="max-h-80 overflow-y-auto">
                    <div v-if="recentAlerts.length === 0" class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay alertas activas</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Todos los vehículos operan normalmente</p>
                    </div>

                    <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div 
                            v-for="alert in recentAlerts" 
                            :key="alert.id"
                            class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                            @click="selectAlert(alert)"
                        >
                            <div class="flex items-start space-x-3">
                                <div :class="getPriorityIconClass(alert.priority)" class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ alert.title }}
                                        </p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ alert.detected_at }}</span>
                                    </div>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span :class="getPriorityBadgeClass(alert.priority)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ alert.priority_label }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ alert.vehicle?.license_plate || 'Sin vehículo' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                        {{ alert.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de monitoreo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-700 dark:text-red-300">Críticas</p>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ criticalCount }}</p>
                    </div>
                    <div class="p-2 bg-red-200 dark:bg-red-800 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-700 dark:text-orange-300">Alta Prioridad</p>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ highCount }}</p>
                    </div>
                    <div class="p-2 bg-orange-200 dark:bg-orange-800 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Media Prioridad</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ mediumCount }}</p>
                    </div>
                    <div class="p-2 bg-yellow-200 dark:bg-yellow-800 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Baja Prioridad</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ lowCount }}</p>
                    </div>
                    <div class="p-2 bg-blue-200 dark:bg-blue-800 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

interface Props {
    data: {
        vehicles_with_alerts?: Array<any>
        map_center?: {
            latitude: number
            longitude: number
        }
    }
    recentAlerts: Array<any>
}

const props = defineProps<Props>()

const mapLoading = ref(true)
const map = ref<L.Map | null>(null)
const mapContainer = ref<HTMLElement>()
const alertMarkers = ref<Map<number, L.Marker>>(new Map())

// Computed properties para estadísticas
const criticalCount = computed(() => props.recentAlerts.filter(alert => alert.priority === 'critica').length)
const highCount = computed(() => props.recentAlerts.filter(alert => alert.priority === 'alta').length)
const mediumCount = computed(() => props.recentAlerts.filter(alert => alert.priority === 'media').length)
const lowCount = computed(() => props.recentAlerts.filter(alert => alert.priority === 'baja').length)

// Métodos para estilos
const getPriorityIconClass = (priority: string): string => {
    const classes: Record<string, string> = {
        'critica': 'bg-red-500',
        'alta': 'bg-orange-500',
        'media': 'bg-yellow-500',
        'baja': 'bg-blue-500'
    }
    return classes[priority] || 'bg-gray-500'
}

const getPriorityBadgeClass = (priority: string): string => {
    const classes: Record<string, string> = {
        'critica': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
        'alta': 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
        'media': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
        'baja': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300'
    }
    return classes[priority] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
}

// Métodos de interacción
const selectAlert = (alert: any) => {
    router.visit(route('alerts.show', alert.id))
}

// Configuración del mapa
const mapConfig = {
    center: [10.6678, -63.2583] as [number, number], // Carúpano, Venezuela
    zoom: 13,
    minZoom: 10,
    maxZoom: 18
}

// Inicialización del mapa
onMounted(() => {
    initializeMap()
})

const initializeMap = () => {
    if (!mapContainer.value) return

    try {
        // Crear el mapa
        map.value = L.map(mapContainer.value).setView(mapConfig.center, mapConfig.zoom)

        // Agregar capa de tiles de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            minZoom: mapConfig.minZoom,
            maxZoom: mapConfig.maxZoom
        }).addTo(map.value)

        // Configurar iconos personalizados
        setupCustomIcons()

        // Cargar alertas en el mapa
        loadAlertsOnMap()

        mapLoading.value = false
    } catch (error) {
        console.error('Error initializing map:', error)
        mapLoading.value = false
    }
}

const setupCustomIcons = () => {
    // Configurar iconos por defecto de Leaflet
    delete (L.Icon.Default.prototype as any)._getIconUrl
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon-2x.png',
        iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
    })
}

// Crear iconos personalizados por prioridad de alerta
const getAlertIcon = (priority: string) => {
    const colors = {
        'critica': '#ef4444',   // Rojo
        'alta': '#f97316',      // Naranja
        'media': '#eab308',     // Amarillo
        'baja': '#3b82f6'       // Azul
    }

    const color = colors[priority as keyof typeof colors] || '#6b7280'

    return L.divIcon({
        html: `
            <div style="
                background-color: ${color};
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 2px solid white;
                box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <svg style="width: 12px; height: 12px; color: white;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
        `,
        className: 'custom-alert-icon',
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    })
}

const loadAlertsOnMap = () => {
    if (!map.value) return

    // Limpiar marcadores existentes
    alertMarkers.value.forEach(marker => {
        map.value?.removeLayer(marker)
    })
    alertMarkers.value.clear()

    // Agregar marcadores para cada alerta
    props.recentAlerts.forEach((alert, index) => {
        // Simular coordenadas aleatorias cerca de Carúpano para las alertas
        const lat = mapConfig.center[0] + (Math.random() - 0.5) * 0.02
        const lng = mapConfig.center[1] + (Math.random() - 0.5) * 0.02

        const marker = L.marker([lat, lng], {
            icon: getAlertIcon(alert.priority)
        }).addTo(map.value!)

        // Popup con información de la alerta
        marker.bindPopup(`
            <div class="p-2">
                <h4 class="font-semibold text-sm">${alert.title}</h4>
                <p class="text-xs text-gray-600 mt-1">${alert.description}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs px-2 py-1 rounded-full ${getPriorityBadgeClass(alert.priority)}">${alert.priority_label}</span>
                    <span class="text-xs text-gray-500">${alert.detected_at}</span>
                </div>
            </div>
        `)

        alertMarkers.value.set(alert.id, marker)
    })
}

onUnmounted(() => {
    if (map.value) {
        map.value.remove()
    }
})
</script>
