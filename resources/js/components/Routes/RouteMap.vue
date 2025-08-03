<template>
    <div class="route-map-container w-full">
        <div class="map-header bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 border-b-2 border-gray-200 dark:border-gray-600 px-6 py-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mapa de Ruta</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Haz clic en el mapa para agregar puntos de recolección</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        @click="clearAllPoints"
                        class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 text-sm leading-4 font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    >
                        <TrashIcon class="w-4 h-4 mr-1" />
                        Limpiar Todo
                    </button>
                    <button
                        v-if="collectionPoints.length >= 2"
                        @click="optimizeRoute"
                        :disabled="optimizing"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition-colors"
                    >
                        <span v-if="optimizing">Optimizando...</span>
                        <span v-else>Optimizar Ruta</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="map-content relative bg-gray-100 dark:bg-slate-900">
            <div id="route-map" class="w-full h-[500px] border-t border-gray-200 dark:border-gray-600"></div>
            
            <!-- Map Controls -->
            <div class="absolute top-4 right-4 bg-white dark:bg-slate-800 rounded-lg shadow-lg p-3 space-y-2 z-[1000] border border-gray-200 dark:border-gray-600">
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">Leyenda</div>
                <div class="flex flex-col space-y-1 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        Puntos de recolección
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        Ruta optimizada
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        Punto de inicio
                    </div>
                </div>
            </div>

            <!-- Points List -->
            <div v-if="collectionPoints.length > 0" class="absolute bottom-4 left-4 bg-white dark:bg-slate-800 rounded-lg shadow-lg p-3 max-w-xs z-[1000] border border-gray-200 dark:border-gray-600">
                <div class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Puntos de Recolección ({{ collectionPoints.length }})</div>
                <div class="max-h-32 overflow-y-auto space-y-1">
                    <div
                        v-for="(point, index) in collectionPoints"
                        :key="index"
                        class="flex items-center justify-between text-xs p-2 bg-gray-50 dark:bg-slate-700 rounded"
                    >
                        <div class="flex-1 truncate">
                            <div class="font-medium text-gray-900 dark:text-white">Punto {{ index + 1 }}</div>
                            <div class="text-gray-500 dark:text-gray-400 truncate">{{ point.address || 'Sin dirección' }}</div>
                        </div>
                        <button
                            @click="removePoint(index)"
                            class="ml-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                        >
                            <TrashIcon class="w-3 h-3" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Statistics -->
        <div v-if="routeStats" class="map-footer bg-gray-50 border-t border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center">
                    <MapIcon class="w-4 h-4 text-blue-500 mr-2" />
                    <span class="text-gray-600">Distancia Total:</span>
                    <span class="ml-1 font-medium">{{ routeStats.totalDistance }} km</span>
                </div>
                <div class="flex items-center">
                    <ClockIcon class="w-4 h-4 text-green-500 mr-2" />
                    <span class="text-gray-600">Tiempo Estimado:</span>
                    <span class="ml-1 font-medium">{{ routeStats.estimatedTime }}</span>
                </div>
                <div class="flex items-center">
                    <TruckIcon class="w-4 h-4 text-purple-500 mr-2" />
                    <span class="text-gray-600">Puntos:</span>
                    <span class="ml-1 font-medium">{{ collectionPoints.length }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import 'leaflet-routing-machine'
import '../../../css/leaflet-routing.css'
import {
    TrashIcon,
    MapIcon,
    ClockIcon,
    TruckIcon
} from '@heroicons/vue/24/outline'

interface CollectionPoint {
    lat: number
    lng: number
    address: string
    priority?: number
}

interface RouteStats {
    totalDistance: string
    estimatedTime: string
}

interface Props {
    modelValue: CollectionPoint[]
    startPoint?: { lat: number; lng: number; address: string }
    readonly?: boolean
}

interface Emits {
    (e: 'update:modelValue', value: CollectionPoint[]): void
    (e: 'pointAdded', point: CollectionPoint): void
    (e: 'pointRemoved', index: number): void
    (e: 'routeOptimized', optimizedPoints: CollectionPoint[]): void
}

const props = withDefaults(defineProps<Props>(), {
    readonly: false
})

const emit = defineEmits<Emits>()

// Reactive data
const map = ref<L.Map | null>(null)
const collectionPoints = ref<CollectionPoint[]>([])
const markers = ref<L.Marker[]>([])
const routeLine = ref<L.Polyline | null>(null)
const routingControl = ref<any>(null)
const optimizing = ref(false)
const routeStats = ref<RouteStats | null>(null)

// Carúpano coordinates
const CARUPANO_CENTER = { lat: 10.6678, lng: -63.2583 }

// Function definitions (must be defined before watchers)
const updateMapMarkers = () => {
    if (!map.value) return

    // Clear existing markers
    markers.value.forEach(marker => map.value?.removeLayer(marker))
    markers.value = []

    // Clear existing route line and routing control
    if (routeLine.value) {
        map.value.removeLayer(routeLine.value)
        routeLine.value = null
    }

    if (routingControl.value) {
        map.value.removeControl(routingControl.value)
        routingControl.value = null
    }

    // Add markers for collection points
    collectionPoints.value.forEach((point, index) => {
        const marker = L.marker([point.lat, point.lng], {
            icon: L.divIcon({
                className: 'custom-marker',
                html: `<div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">${index + 1}</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        })

        if (!props.readonly) {
            marker.bindPopup(`
                <div class="p-2">
                    <p class="font-semibold">Punto ${index + 1}</p>
                    <p class="text-sm text-gray-600">${point.address}</p>
                    <p class="text-xs text-gray-500 mt-1">Haz clic en el punto de la lista lateral para eliminarlo</p>
                </div>
            `)
        } else {
            marker.bindPopup(`
                <div class="p-2">
                    <p class="font-semibold">Punto ${index + 1}</p>
                    <p class="text-sm text-gray-600">${point.address}</p>
                </div>
            `)
        }

        marker.addTo(map.value!)
        markers.value.push(marker)
    })

    // Create routing between points if we have 2 or more points
    if (collectionPoints.value.length >= 2) {
        createRouting()
    }
}

const createRouting = () => {
    if (!map.value || collectionPoints.value.length < 2) return

    // Check if routing library is available
    if (!(L as any).Routing) {
        console.warn('Leaflet Routing Machine not available, falling back to simple polyline')
        createSimpleRoute()
        return
    }

    const waypoints = collectionPoints.value.map(point =>
        L.latLng(point.lat, point.lng)
    )

    // Create routing control with custom styling
    routingControl.value = (L as any).Routing.control({
        waypoints: waypoints,
        routeWhileDragging: false,
        addWaypoints: false,
        createMarker: () => null, // Don't create default markers
        lineOptions: {
            styles: [
                { color: '#3b82f6', weight: 6, opacity: 0.8 },
                { color: '#1d4ed8', weight: 4, opacity: 1 }
            ]
        },
        router: (L as any).Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1',
            profile: 'driving'
        }),
        show: false, // Hide the routing instructions panel
        collapsible: false
    }).on('routesfound', function(e: any) {
        const routes = e.routes
        if (routes && routes.length > 0) {
            const route = routes[0]
            updateRouteStats(route)
        }
    }).addTo(map.value)
}

const createSimpleRoute = () => {
    if (!map.value || collectionPoints.value.length < 2) return

    const latlngs = collectionPoints.value.map(point => [point.lat, point.lng] as [number, number])
    routeLine.value = L.polyline(latlngs, {
        color: '#3b82f6',
        weight: 4,
        opacity: 0.8
    }).addTo(map.value!)

    // Calculate simple stats
    calculateRouteStats()
}

const updateRouteStats = (route: any) => {
    if (!route) {
        routeStats.value = null
        return
    }

    const totalDistance = (route.summary.totalDistance / 1000).toFixed(1) // Convert to km
    const totalTime = Math.round(route.summary.totalTime / 60) // Convert to minutes

    const hours = Math.floor(totalTime / 60)
    const minutes = totalTime % 60

    routeStats.value = {
        totalDistance: totalDistance,
        estimatedTime: hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`
    }
}

const calculateRouteStats = () => {
    if (collectionPoints.value.length < 2) {
        routeStats.value = null
        return
    }

    let totalDistance = 0

    // Calculate total distance
    for (let i = 0; i < collectionPoints.value.length - 1; i++) {
        const point1 = collectionPoints.value[i]
        const point2 = collectionPoints.value[i + 1]

        const distance = calculateDistance(point1.lat, point1.lng, point2.lat, point2.lng)
        totalDistance += distance
    }

    // Estimate time (assuming 30 km/h average speed + 10 minutes per stop)
    const drivingTime = (totalDistance / 30) * 60 // minutes
    const stopTime = collectionPoints.value.length * 10 // 10 minutes per stop
    const totalTime = drivingTime + stopTime

    const hours = Math.floor(totalTime / 60)
    const minutes = Math.round(totalTime % 60)

    routeStats.value = {
        totalDistance: totalDistance.toFixed(1),
        estimatedTime: hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`
    }
}

const calculateDistance = (lat1: number, lng1: number, lat2: number, lng2: number): number => {
    const R = 6371 // Earth's radius in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180
    const dLng = (lng2 - lng1) * Math.PI / 180
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2)
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))
    return R * c
}

// Watch for changes in modelValue
watch(() => props.modelValue, (newPoints) => {
    // Solo actualizar si realmente hay diferencias para evitar bucle infinito
    if (JSON.stringify(collectionPoints.value) !== JSON.stringify(newPoints)) {
        collectionPoints.value = [...newPoints]
        updateMapMarkers()
        if (newPoints.length >= 2) {
            calculateRouteStats()
        } else {
            routeStats.value = null
        }
    }
}, { immediate: true, deep: true })

// Watch for changes in collection points
watch(collectionPoints, (newPoints) => {
    // Solo emitir si realmente hay diferencias para evitar bucle infinito
    if (JSON.stringify(props.modelValue) !== JSON.stringify(newPoints)) {
        emit('update:modelValue', newPoints)
    }

    // Actualizar marcadores y estadísticas
    updateMapMarkers()
    if (newPoints.length >= 2) {
        calculateRouteStats()
    } else {
        routeStats.value = null
    }
}, { deep: true })

onMounted(() => {
    initializeMap()
})

onUnmounted(() => {
    if (map.value) {
        map.value.remove()
    }
})

const initializeMap = () => {
    // Initialize map
    map.value = L.map('route-map', {
        zoomControl: true,
        attributionControl: true
    }).setView([CARUPANO_CENTER.lat, CARUPANO_CENTER.lng], 14)

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map.value)

    // Add click event to add points (only if not readonly)
    if (!props.readonly) {
        map.value.on('click', onMapClick)
    }

    // Add start point if provided
    if (props.startPoint) {
        addStartPointMarker(props.startPoint)
    }

    // Initialize existing points
    updateMapMarkers()
}

const onMapClick = async (e: L.LeafletMouseEvent) => {
    const { lat, lng } = e.latlng

    // Get address from coordinates (reverse geocoding simulation)
    const address = await getAddressFromCoordinates(lat, lng)

    const newPoint: CollectionPoint = {
        lat: Number(lat.toFixed(6)),
        lng: Number(lng.toFixed(6)),
        address,
        priority: collectionPoints.value.length + 1
    }

    collectionPoints.value.push(newPoint)
    emit('pointAdded', newPoint)
}

const getAddressFromCoordinates = async (lat: number, lng: number): Promise<string> => {
    // Simulate reverse geocoding - in a real app, you'd use a geocoding service
    const streets = [
        'Av. Independencia', 'Calle Bolívar', 'Calle Miranda', 'Calle Sucre',
        'Av. Perimetral', 'Calle Mariño', 'Calle Bermúdez', 'Av. Universidad'
    ]
    
    const randomStreet = streets[Math.floor(Math.random() * streets.length)]
    const randomNumber = Math.floor(Math.random() * 200) + 1
    
    return `${randomStreet} #${randomNumber}, Carúpano`
}



const addStartPointMarker = (startPoint: { lat: number; lng: number; address: string }) => {
    if (!map.value) return

    const startMarker = L.marker([startPoint.lat, startPoint.lng], {
        icon: L.divIcon({
            className: 'custom-marker',
            html: `<div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">S</div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        })
    })

    startMarker.bindPopup(`
        <div class="text-sm">
            <strong>Punto de Inicio</strong><br>
            ${startPoint.address}<br>
            <small>Lat: ${startPoint.lat}, Lng: ${startPoint.lng}</small>
        </div>
    `)

    startMarker.addTo(map.value)
}

const removePoint = (index: number) => {
    collectionPoints.value.splice(index, 1)
    // Reajustar prioridades
    collectionPoints.value.forEach((point, i) => {
        point.priority = i + 1
    })
    emit('pointRemoved', index)
}

const clearAllPoints = () => {
    collectionPoints.value = []
    routeStats.value = null
}

const optimizeRoute = async () => {
    if (collectionPoints.value.length < 2) return

    optimizing.value = true

    try {
        // Simulate optimization algorithm with better logic
        await new Promise(resolve => setTimeout(resolve, 1500))

        // Nearest neighbor algorithm for better route optimization
        const optimizedPoints = nearestNeighborOptimization([...collectionPoints.value])

        // Update priorities
        optimizedPoints.forEach((point, index) => {
            point.priority = index + 1
        })

        collectionPoints.value = optimizedPoints
        emit('routeOptimized', optimizedPoints)

    } finally {
        optimizing.value = false
    }
}

const nearestNeighborOptimization = (points: CollectionPoint[]): CollectionPoint[] => {
    if (points.length <= 2) return points

    const optimized: CollectionPoint[] = []
    const remaining = [...points]

    // Start with the first point (or closest to center)
    let current = remaining.reduce((closest, point) => {
        const distCurrent = calculateDistance(point.lat, point.lng, CARUPANO_CENTER.lat, CARUPANO_CENTER.lng)
        const distClosest = calculateDistance(closest.lat, closest.lng, CARUPANO_CENTER.lat, CARUPANO_CENTER.lng)
        return distCurrent < distClosest ? point : closest
    })

    optimized.push(current)
    remaining.splice(remaining.indexOf(current), 1)

    // Find nearest neighbor for each subsequent point
    while (remaining.length > 0) {
        let nearest = remaining[0]
        let minDistance = calculateDistance(current.lat, current.lng, nearest.lat, nearest.lng)

        for (let i = 1; i < remaining.length; i++) {
            const distance = calculateDistance(current.lat, current.lng, remaining[i].lat, remaining[i].lng)
            if (distance < minDistance) {
                minDistance = distance
                nearest = remaining[i]
            }
        }

        optimized.push(nearest)
        remaining.splice(remaining.indexOf(nearest), 1)
        current = nearest
    }

    return optimized
}


</script>

<style scoped>
.route-map-container {
    background-color: white;
    border-radius: 0.75rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.05);
    overflow: hidden;
}

#route-map {
    height: 500px;
    z-index: 1;
}

:deep(.custom-marker) {
    background: transparent;
    border: none;
}

:deep(.leaflet-popup-content-wrapper) {
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
}

:deep(.leaflet-routing-container) {
    display: none !important;
}

:deep(.leaflet-control-zoom) {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    z-index: 999 !important;
}

:deep(.leaflet-control-zoom a) {
    border-radius: 0.25rem;
    border: none;
    background: white;
    color: #374151;
    font-weight: bold;
}

:deep(.leaflet-control-zoom a:hover) {
    background: #f3f4f6;
    color: #059669;
}

:deep(.leaflet-routing-alt) {
    display: none !important;
}

/* Ensure map controls are visible but below our custom controls */
:deep(.leaflet-control) {
    z-index: 999 !important;
}

/* Style the attribution */
:deep(.leaflet-control-attribution) {
    background: rgba(255, 255, 255, 0.8);
    border-radius: 0.25rem;
    font-size: 0.75rem;
    z-index: 999 !important;
}

/* Ensure our custom controls are always on top */
.absolute {
    z-index: 1000 !important;
}

/* Ensure map container uses full width */
.route-map-container {
    width: 100% !important;
    max-width: none !important;
}

#route-map {
    width: 100% !important;
    min-height: 500px;
}
</style>
