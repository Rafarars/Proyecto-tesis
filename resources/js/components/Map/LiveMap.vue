<template>
    <div class="live-map-container">
        <!-- Header del mapa -->
        <div class="map-header bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Mapa en Vivo</h2>
                    <p class="text-sm text-gray-600">Seguimiento en tiempo real de vehículos de recolección</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Indicador de estado -->
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-gray-600">En línea</span>
                    </div>
                    <!-- Última actualización -->
                    <div class="text-sm text-gray-500">
                        Actualizado: {{ lastUpdate }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Controles del mapa -->
        <div class="map-controls bg-gray-50 border-b border-gray-200 p-3">
            <div class="flex items-center space-x-4">
                <!-- Filtros de vehículos -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Mostrar:</label>
                    <button
                        v-for="filter in vehicleFilters"
                        :key="filter.type"
                        @click="toggleFilter(filter.type)"
                        :class="[
                            'px-3 py-1 rounded-full text-xs font-medium transition-colors',
                            filter.active 
                                ? 'bg-blue-100 text-blue-800 border border-blue-200' 
                                : 'bg-gray-100 text-gray-600 border border-gray-200'
                        ]"
                    >
                        <span :class="filter.color" class="w-2 h-2 rounded-full inline-block mr-1"></span>
                        {{ filter.label }}
                    </button>
                </div>

                <!-- Controles de seguimiento -->
                <div class="flex items-center space-x-2 ml-auto">
                    <button
                        @click="toggleAutoUpdate"
                        :class="[
                            'px-3 py-1 rounded text-sm font-medium transition-colors',
                            autoUpdate 
                                ? 'bg-green-100 text-green-800 border border-green-200' 
                                : 'bg-gray-100 text-gray-600 border border-gray-200'
                        ]"
                    >
                        {{ autoUpdate ? 'Auto-actualización ON' : 'Auto-actualización OFF' }}
                    </button>
                    <button
                        @click="centerMap"
                        class="px-3 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded text-sm font-medium hover:bg-blue-200 transition-colors"
                    >
                        Centrar en Carúpano
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenedor del mapa -->
        <div class="map-wrapper relative">
            <div 
                ref="mapContainer" 
                id="live-map" 
                class="w-full h-[600px] bg-gray-100"
            ></div>

            <!-- Panel de información del vehículo seleccionado -->
            <div
                v-if="selectedVehicle"
                class="absolute top-4 right-4 bg-white rounded-lg shadow-lg border border-gray-200 p-4 w-80 z-[1000]"
            >
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900">{{ selectedVehicle.license_plate }}</h3>
                    <button 
                        @click="selectedVehicle = null"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipo:</span>
                        <span class="font-medium">{{ selectedVehicle.type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estado:</span>
                        <span 
                            :class="getStatusColor(selectedVehicle.status)"
                            class="px-2 py-1 rounded-full text-xs font-medium"
                        >
                            {{ selectedVehicle.status }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Velocidad:</span>
                        <span class="font-medium">{{ selectedVehicle.speed || 0 }} km/h</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última actualización:</span>
                        <span class="font-medium">{{ formatTime(selectedVehicle.updated_at) }}</span>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-200">
                    <button
                        @click="followVehicle(selectedVehicle)"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded text-sm font-medium hover:bg-blue-700 transition-colors"
                    >
                        Seguir Vehículo
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="map-footer bg-white border-t border-gray-200 p-4">
            <div class="grid grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ activeVehicles }}</div>
                    <div class="text-sm text-gray-600">Activos</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ totalDistance }}</div>
                    <div class="text-sm text-gray-600">km recorridos hoy</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-600">{{ averageSpeed }}</div>
                    <div class="text-sm text-gray-600">km/h promedio</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-600">{{ routesCompleted }}</div>
                    <div class="text-sm text-gray-600">rutas completadas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventanas emergentes de rutas -->
    <div v-for="[routeId, routeData] in routePopups" :key="routeId"
         v-show="routeData.visible"
         class="route-popup fixed top-4 right-4 bg-white rounded-lg shadow-lg border border-gray-200 p-4 max-w-sm"
         style="z-index: 10000;">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900">{{ routeData.route.name }}</h3>
            <button @click="hideRoutePopup(routeId)"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Código:</span>
                <span class="font-medium">{{ routeData.route.code }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Puntos:</span>
                <span class="font-medium">{{ routeData.route.route_points?.length || 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Estado:</span>
                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">Activa</span>
            </div>
        </div>

        <div class="mt-3 pt-3 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Ruta trazada siguiendo las carreteras disponibles.
                Al cerrar esta ventana, la ruta se ocultará del mapa.
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import 'leaflet-routing-machine'
import '../../../css/leaflet-routing.css'

// Declaración global para TypeScript
declare global {
    interface Window {
        viewVehicleDetails: (vehicleId: number) => void
        showRoutePopup: (routeId: number) => void
        hideRoutePopup: (routeId: number) => void
    }
}

// Interfaces
interface Vehicle {
    id: number
    license_plate: string
    type: string
    status: string
    current_location: {
        lat: number
        lng: number
    }
    speed?: number
    updated_at: string
    assigned_route?: {
        id: number
        name: string
        code: string
        status: string
        type: string
        collection_points: Array<{
            lat: number
            lng: number
            address: string
            priority?: number
        }>
        optimized_sequence?: any[]
        total_distance_km?: number
        estimated_duration_minutes?: number
    } | null
    driver?: {
        id: number
        full_name: string
        employee_code: string
    } | null
}

// Refs reactivos
const mapContainer = ref<HTMLElement>()
const map = ref<L.Map>()
const vehicles = ref<Vehicle[]>([])
const selectedVehicle = ref<Vehicle | null>(null)
const autoUpdate = ref(true)
const lastUpdate = ref('')

// Configuración del mapa
const mapConfig = {
    center: [10.6678, -63.2583] as [number, number], // Carúpano, Venezuela
    zoom: 13,
    minZoom: 10,
    maxZoom: 18
}

// Filtros de vehículos
const vehicleFilters = ref([
    { type: 'compactador', label: 'Compactadores', active: true, color: 'bg-green-500' },
    { type: 'camion', label: 'Camiones', active: true, color: 'bg-blue-500' },
    { type: 'pickup', label: 'Pickups', active: true, color: 'bg-orange-500' },
    { type: 'van', label: 'Vans', active: true, color: 'bg-purple-500' }
])

// Marcadores de vehículos y rutas
const vehicleMarkers = ref<Map<number, L.Marker>>(new Map())
const routeLines = ref<Map<number, L.Polyline>>(new Map())
const routePointMarkers = ref<Map<number, L.Marker[]>>(new Map())
const routingControls = ref<Map<number, any>>(new Map())

// Control de ventanas emergentes de rutas
const routePopups = ref<Map<number, { visible: boolean; route: any }>>(new Map())

// Computed properties
const activeVehicles = computed(() => 
    vehicles.value.filter(v => v.status === 'activo').length
)

const totalDistance = computed(() => '45.2') // Simulado
const averageSpeed = computed(() => '28') // Simulado  
const routesCompleted = computed(() => '3') // Simulado

// Métodos
const initializeMap = () => {
    if (!mapContainer.value) return

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
    
    // Cargar vehículos iniciales
    loadVehicles()
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

// Crear iconos personalizados por tipo de vehículo
const getVehicleIcon = (type: string, status: string) => {
    const colors = {
        'compactador': '#10b981', // Verde
        'camion': '#3b82f6',      // Azul
        'pickup': '#f59e0b',      // Naranja
        'van': '#8b5cf6'          // Púrpura
    }

    const color = colors[type as keyof typeof colors] || '#6b7280'
    const opacity = status === 'activo' ? '1' : '0.6'

    const svgIcon = `
        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12.5" cy="12.5" r="10" fill="${color}" stroke="white" stroke-width="2" opacity="${opacity}"/>
            <circle cx="12.5" cy="12.5" r="4" fill="white"/>
        </svg>
    `

    return L.divIcon({
        html: svgIcon,
        className: 'custom-vehicle-icon',
        iconSize: [25, 25],
        iconAnchor: [12.5, 12.5]
    })
}

const loadVehicles = async () => {
    try {
        // Cargar vehículos reales desde la API
        const response = await fetch('/api/live-vehicles')
        const data = await response.json()

        if (data.vehicles) {
            vehicles.value = data.vehicles.map((vehicle: any) => ({
                id: vehicle.id,
                license_plate: vehicle.license_plate,
                type: vehicle.type,
                status: vehicle.status,
                current_location: vehicle.current_location,
                speed: vehicle.current_speed,
                updated_at: vehicle.updated_at,
                assigned_route: vehicle.assigned_route,
                driver: vehicle.driver
            }))
        } else {
            // Fallback a datos simulados si no hay vehículos en la BD
            vehicles.value = [
                {
                    id: 1,
                    license_plate: 'CAR-001',
                    type: 'compactador',
                    status: 'activo',
                    current_location: { lat: 10.6678, lng: -63.2583 },
                    speed: 25,
                    updated_at: new Date().toISOString(),
                    assigned_route: null,
                    driver: null
                }
            ]
        }

        updateVehicleMarkers()
        updateLastUpdate()

        // Verificar si hay un vehículo específico en la URL
        checkUrlParams()
    } catch (error) {
        console.error('Error loading vehicles:', error)
        // En caso de error, usar datos simulados
        vehicles.value = []
    }
}

const updateVehicleMarkers = () => {
    if (!map.value) return

    vehicles.value.forEach(vehicle => {
        const isVisible = vehicleFilters.value.find(f => f.type === vehicle.type)?.active

        if (!isVisible) {
            // Ocultar marcador si el filtro está desactivado
            const marker = vehicleMarkers.value.get(vehicle.id)
            if (marker) {
                map.value?.removeLayer(marker)
            }
            return
        }

        let marker = vehicleMarkers.value.get(vehicle.id)
        
        if (!marker) {
            // Crear nuevo marcador con icono personalizado
            marker = L.marker([vehicle.current_location.lat, vehicle.current_location.lng], {
                icon: getVehicleIcon(vehicle.type, vehicle.status)
            })
                .bindPopup(`
                    <div class="text-center p-2">
                        <strong class="text-lg">${vehicle.license_plate}</strong><br>
                        <span class="text-sm text-gray-600">Tipo:</span> <span class="font-medium">${vehicle.type}</span><br>
                        <span class="text-sm text-gray-600">Estado:</span> <span class="font-medium">${vehicle.status}</span><br>
                        <span class="text-sm text-gray-600">Velocidad:</span> <span class="font-medium">${vehicle.speed || 0} km/h</span><br>
                        ${vehicle.assigned_route ? `<span class="text-sm text-gray-600">Ruta:</span> <span class="font-medium">${vehicle.assigned_route.code}</span><br>` : ''}
                        ${vehicle.driver ? `<span class="text-sm text-gray-600">Conductor:</span> <span class="font-medium">${vehicle.driver.full_name}</span><br>` : ''}
                        <button onclick="window.viewVehicleDetails(${vehicle.id})" class="mt-2 px-3 py-1 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
                            Ver Detalles
                        </button>
                    </div>
                `)
                .on('click', () => {
                    selectedVehicle.value = vehicle
                })
                .addTo(map.value!)

            vehicleMarkers.value.set(vehicle.id, marker)
        } else {
            // Actualizar posición e icono existente
            marker.setLatLng([vehicle.current_location.lat, vehicle.current_location.lng])
            marker.setIcon(getVehicleIcon(vehicle.type, vehicle.status))
        }

        // Mostrar ruta asignada si existe (solo si no existe ya)
        if (vehicle.assigned_route && !routePointMarkers.value.has(vehicle.id)) {
            updateVehicleRoute(vehicle)
        }
    })
}

const updateVehicleRoute = (vehicle: Vehicle) => {
    if (!map.value || !vehicle.assigned_route) return

    const routeId = vehicle.id

    // Limpiar ruta anterior si existe
    clearVehicleRoute(routeId)

    // Solo crear los marcadores de puntos, NO la ruta completa
    const route = vehicle.assigned_route
    if (route.route_points && route.route_points.length > 0) {
        createRoutePointsOnly(vehicle, route)
    }
}

const clearVehicleRoute = (routeId: number) => {
    try {
        // Limpiar routing control
        const existingRouting = routingControls.value.get(routeId)
        if (existingRouting && map.value) {
            try {
                map.value.removeControl(existingRouting)
            } catch (e) {
                console.warn('Error removing existing routing control:', e)
            }
            routingControls.value.delete(routeId)
        }

        // Limpiar línea de ruta
        const existingRoute = routeLines.value.get(routeId)
        if (existingRoute && map.value) {
            if (map.value.hasLayer(existingRoute)) {
                map.value.removeLayer(existingRoute)
            }
            routeLines.value.delete(routeId)
        }

        // Limpiar marcadores de puntos
        const existingPoints = routePointMarkers.value.get(routeId)
        if (existingPoints) {
            existingPoints.forEach(marker => {
                if (map.value && map.value.hasLayer(marker)) {
                    map.value.removeLayer(marker)
                }
            })
            routePointMarkers.value.delete(routeId)
        }

        // NO limpiar popup data durante actualizaciones normales
        // Solo limpiar cuando se elimine completamente el vehículo
    } catch (error) {
        console.warn('Error clearing vehicle route:', error)
    }
}

// Función para limpiar completamente un vehículo (incluyendo popups)
const clearVehicleCompletely = (routeId: number) => {
    clearVehicleRoute(routeId)
    // Limpiar popup data
    routePopups.value.delete(routeId)
}

// Función para crear solo los puntos de ruta (sin el trazado)
const createRoutePointsOnly = (vehicle: Vehicle, route: any) => {
    if (!map.value || !route.route_points || route.route_points.length === 0) return

    const routeId = vehicle.id

    // Solo crear los marcadores de puntos, NO el trazado de la ruta
    createRoutePointMarkers(route, routeId)
}

const createRoutingForVehicle = (vehicle: Vehicle, route: any) => {
    if (!map.value || !route.route_points || route.route_points.length < 2) return

    const routeId = vehicle.id

    // Verificar si Leaflet Routing Machine está disponible
    if ((L as any).Routing) {
        // Usar routing real que sigue carreteras
        const waypoints = route.route_points.map((point: any) =>
            L.latLng(point.lat, point.lng)
        )

        const routingControl = (L as any).Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypoints: false,
            createMarker: () => null, // No crear marcadores por defecto
            lineOptions: {
                styles: [
                    { color: '#3b82f6', weight: 6, opacity: 0.6 },
                    { color: '#1d4ed8', weight: 4, opacity: 0.8 }
                ]
            },
            router: (L as any).Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'driving'
            }),
            show: false, // Ocultar panel de instrucciones
            collapsible: false
        }).addTo(map.value)

        routingControls.value.set(routeId, routingControl)
    } else {
        // Fallback a línea simple si no está disponible el routing
        const routeCoords = route.route_points.map((point: any) => [point.lat, point.lng])
        const routeLine = L.polyline(routeCoords, {
            color: '#3b82f6',
            weight: 4,
            opacity: 0.8
        }).addTo(map.value)

        routeLines.value.set(routeId, routeLine)
    }

    // Crear marcadores para los puntos de la ruta
    createRoutePointMarkers(route, routeId)
}

// Función separada para crear marcadores de puntos
const createRoutePointMarkers = (route: any, routeId: number) => {
    if (!map.value || !route.route_points) return

    const pointMarkers = route.route_points.map((point: any, index: number) => {
        const isStart = index === 0
        const isEnd = index === route.route_points.length - 1

        return L.circleMarker([point.lat, point.lng], {
            radius: 8,
            fillColor: isStart ? '#ef4444' : (isEnd ? '#ef4444' : '#3b82f6'), // Rojo para inicio/fin
            color: isStart ? '#dc2626' : (isEnd ? '#dc2626' : '#1d4ed8'),
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        })
        .bindPopup(`
            <div class="text-center p-2">
                <strong>${isStart ? 'Punto de Inicio' : (isEnd ? 'Punto Final' : `Punto ${index + 1}`)}</strong><br>
                <span class="text-sm">${point.address || 'Punto de ruta'}</span><br>
                <span class="text-xs text-gray-500">Ruta: ${route.code}</span>
                <div class="mt-2">
                    <button onclick="window.showRoutePopup(${routeId})" class="px-2 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">
                        Mostrar Ruta en Mapa
                    </button>
                </div>
            </div>
        `)
        .addTo(map.value!)
    })

    routePointMarkers.value.set(routeId, pointMarkers)

    // Registrar la ruta en el control de popups (inicialmente oculta)
    routePopups.value.set(routeId, { visible: false, route })
}

const toggleFilter = (type: string) => {
    const filter = vehicleFilters.value.find(f => f.type === type)
    if (filter) {
        filter.active = !filter.active
        updateVehicleMarkers()
    }
}

const toggleAutoUpdate = () => {
    autoUpdate.value = !autoUpdate.value
}

const centerMap = () => {
    if (map.value) {
        map.value.setView(mapConfig.center, mapConfig.zoom)
    }
}

const followVehicle = (vehicle: Vehicle) => {
    if (map.value) {
        map.value.setView([vehicle.current_location.lat, vehicle.current_location.lng], 16)
    }
}

// Función para centrar y abrir popup de un vehículo específico
const centerOnVehicle = (vehicleId: number) => {
    const vehicle = vehicles.value.find(v => v.id === vehicleId)
    if (vehicle && map.value) {
        // Centrar el mapa en el vehículo
        map.value.setView([vehicle.current_location.lat, vehicle.current_location.lng], 16)

        // Seleccionar el vehículo
        selectedVehicle.value = vehicle

        // Abrir el popup del marcador
        const marker = vehicleMarkers.value.get(vehicleId)
        if (marker) {
            marker.openPopup()
        }
    }
}

// Función para verificar parámetros de URL
const checkUrlParams = () => {
    const urlParams = new URLSearchParams(window.location.search)
    const vehicleId = urlParams.get('vehicle_id')
    const routeId = urlParams.get('route_id')

    if (vehicleId && vehicles.value.length > 0) {
        const id = parseInt(vehicleId)
        // Esperar un poco para asegurar que los marcadores estén creados
        setTimeout(() => {
            centerOnVehicle(id)
        }, 1000)
    }

    if (routeId && vehicles.value.length > 0) {
        const id = parseInt(routeId)
        // Buscar vehículo con esa ruta asignada y centrarlo
        setTimeout(() => {
            centerOnRoute(id)
        }, 1000)
    }
}

// Función para centrar en una ruta específica
const centerOnRoute = (routeId: number) => {
    if (!map.value) return

    // Buscar vehículo con esa ruta asignada
    const vehicleWithRoute = vehicles.value.find(v =>
        v.assigned_route && v.assigned_route.id === routeId
    )

    if (vehicleWithRoute && vehicleWithRoute.assigned_route) {
        // Obtener todos los puntos de la ruta
        const routePoints = vehicleWithRoute.assigned_route.route_points || []

        if (routePoints.length > 0) {
            // Crear bounds para incluir todos los puntos de la ruta
            const bounds = L.latLngBounds([])

            routePoints.forEach(point => {
                bounds.extend([point.lat, point.lng])
            })

            // Ajustar el mapa para mostrar toda la ruta
            map.value.fitBounds(bounds, { padding: [20, 20] })
        } else {
            // Si no hay puntos de ruta, centrar en el vehículo
            centerOnVehicle(vehicleWithRoute.id)
        }

        // Mostrar información de la ruta
        selectedVehicle.value = vehicleWithRoute

        // Resaltar la ruta
        const routeLine = routeLines.value.get(vehicleWithRoute.id)
        if (routeLine) {
            routeLine.setStyle({
                color: '#ef4444', // Rojo para resaltar
                weight: 5,
                opacity: 1
            })

            // Volver al color normal después de 3 segundos
            setTimeout(() => {
                routeLine.setStyle({
                    color: '#3b82f6',
                    weight: 4,
                    opacity: 0.8
                })
            }, 3000)
        }
    }
}

// Función para ver detalles del vehículo
const viewVehicleDetails = (vehicleId: number) => {
    router.visit(`/vehicles/${vehicleId}`)
}

// Funciones para manejar ventanas emergentes de rutas
const showRoutePopup = (routeId: number) => {
    const routeData = routePopups.value.get(routeId)
    if (routeData) {
        // Solo mostrar si no está ya visible (evitar parpadeo)
        if (!routeData.visible) {
            routeData.visible = true
            // Mostrar la ruta en el mapa si no está visible
            showRouteOnMap(routeData.route, routeId)
            // Resaltar la ruta temporalmente
            highlightRoute(routeId)
        }
    }
}

const hideRoutePopup = (routeId: number) => {
    const routeData = routePopups.value.get(routeId)
    if (routeData) {
        routeData.visible = false
        // Ocultar completamente la ruta del mapa
        hideRouteFromMap(routeId)
    }
}

// Función para mostrar ruta en el mapa
const showRouteOnMap = (route: any, routeId: number) => {
    if (!map.value || !route.route_points || route.route_points.length < 2) return

    // Si la ruta ya está visible, no hacer nada
    if (routingControls.value.has(routeId) || routeLines.value.has(routeId)) {
        return
    }

    // Crear el trazado de la ruta
    if ((L as any).Routing) {
        // Usar routing real que sigue carreteras
        const waypoints = route.route_points.map((point: any) =>
            L.latLng(point.lat, point.lng)
        )

        const routingControl = (L as any).Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypoints: false,
            createMarker: () => null, // No crear marcadores por defecto
            lineOptions: {
                styles: [
                    { color: '#3b82f6', weight: 6, opacity: 0.6 },
                    { color: '#1d4ed8', weight: 4, opacity: 0.8 }
                ]
            },
            router: (L as any).Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'driving'
            }),
            show: false, // Ocultar panel de instrucciones
            collapsible: false
        })

        // Agregar evento de clic a la ruta
        routingControl.on('routesfound', function(e: any) {
            const routes = e.routes
            if (routes && routes.length > 0) {
                // Agregar evento de clic a la línea de la ruta
                setTimeout(() => {
                    const routeContainer = map.value?.getContainer().querySelector('.leaflet-routing-container')
                    if (routeContainer) {
                        const routeLine = routeContainer.querySelector('path')
                        if (routeLine) {
                            routeLine.addEventListener('click', () => {
                                showRoutePopup(routeId)
                            })
                            routeLine.style.cursor = 'pointer'
                        }
                    }
                }, 100)
            }
        })

        routingControl.addTo(map.value)
        routingControls.value.set(routeId, routingControl)
    } else {
        // Fallback a línea simple si no está disponible el routing
        const routeCoords = route.route_points.map((point: any) => [point.lat, point.lng])
        const routeLine = L.polyline(routeCoords, {
            color: '#3b82f6',
            weight: 4,
            opacity: 0.8
        })

        // Agregar evento de clic a la línea simple
        routeLine.on('click', () => {
            showRoutePopup(routeId)
        })

        routeLine.addTo(map.value)
        routeLines.value.set(routeId, routeLine)
    }
}

// Función para ocultar ruta del mapa
const hideRouteFromMap = (routeId: number) => {
    if (!map.value) return

    try {
        // Remover control de routing si existe
        const routingControl = routingControls.value.get(routeId)
        if (routingControl) {
            // Verificar que el control esté realmente en el mapa antes de removerlo
            if (map.value.hasLayer && map.value.hasLayer(routingControl)) {
                map.value.removeControl(routingControl)
            } else {
                // Intentar remover de forma segura
                try {
                    map.value.removeControl(routingControl)
                } catch (e) {
                    console.warn('Error removing routing control:', e)
                }
            }
            routingControls.value.delete(routeId)
        }

        // Remover línea de ruta si existe
        const routeLine = routeLines.value.get(routeId)
        if (routeLine) {
            if (map.value.hasLayer(routeLine)) {
                map.value.removeLayer(routeLine)
            }
            routeLines.value.delete(routeId)
        }

        // NO remover marcadores de puntos de ruta - solo ocultar el trazado
        // Los puntos deben permanecer para poder volver a mostrar la ruta
    } catch (error) {
        console.warn('Error hiding route from map:', error)
    }
}

const highlightRoute = (routeId: number) => {
    // Resaltar routing control
    const routingControl = routingControls.value.get(routeId)
    if (routingControl) {
        // El routing control maneja su propio estilo
        return
    }

    // Resaltar línea simple
    const routeLine = routeLines.value.get(routeId)
    if (routeLine) {
        routeLine.setStyle({
            color: '#ef4444', // Rojo para resaltar
            weight: 6,
            opacity: 1
        })
    }
}

const restoreRouteStyle = (routeId: number) => {
    // Restaurar estilo del routing control
    const routingControl = routingControls.value.get(routeId)
    if (routingControl) {
        // El routing control maneja su propio estilo
        return
    }

    // Restaurar estilo de línea simple
    const routeLine = routeLines.value.get(routeId)
    if (routeLine) {
        routeLine.setStyle({
            color: '#3b82f6',
            weight: 4,
            opacity: 0.8
        })
    }
}

const getStatusColor = (status: string) => {
    const colors = {
        'activo': 'bg-green-100 text-green-800',
        'mantenimiento': 'bg-yellow-100 text-yellow-800',
        'inactivo': 'bg-red-100 text-red-800'
    }
    return colors[status as keyof typeof colors] || 'bg-gray-100 text-gray-800'
}

const formatTime = (timestamp: string) => {
    return new Date(timestamp).toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit'
    })
}

const updateLastUpdate = () => {
    lastUpdate.value = new Date().toLocaleTimeString('es-ES', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    })
}

// Rutas predefinidas para cada vehículo (puntos en calles reales de Carúpano)
const vehicleRoutes = {
    1: [ // CAR-001 - Ruta Centro
        { lat: 10.6678, lng: -63.2583 }, // Plaza Bolívar
        { lat: 10.6685, lng: -63.2590 }, // Calle Bolívar
        { lat: 10.6692, lng: -63.2595 }, // Av. Independencia
        { lat: 10.6688, lng: -63.2580 }, // Calle Miranda
        { lat: 10.6675, lng: -63.2575 }, // Calle Sucre
        { lat: 10.6670, lng: -63.2585 }  // Vuelta al inicio
    ],
    2: [ // CAR-002 - Ruta Norte
        { lat: 10.6720, lng: -63.2620 }, // Zona Norte
        { lat: 10.6725, lng: -63.2615 }, // Av. Perimetral
        { lat: 10.6730, lng: -63.2605 }, // Sector El Morro
        { lat: 10.6715, lng: -63.2600 }, // Calle Principal
        { lat: 10.6710, lng: -63.2610 }, // Regreso
        { lat: 10.6720, lng: -63.2620 }  // Vuelta al inicio
    ],
    3: [ // CAR-003 - Detenido (mantenimiento)
        { lat: 10.6640, lng: -63.2540 }  // Taller municipal
    ],
    4: [ // CAR-004 - Ruta Este
        { lat: 10.6700, lng: -63.2500 }, // Zona Este
        { lat: 10.6705, lng: -63.2495 }, // Av. Costanera
        { lat: 10.6695, lng: -63.2485 }, // Malecón
        { lat: 10.6690, lng: -63.2490 }, // Puerto
        { lat: 10.6685, lng: -63.2505 }, // Centro Comercial
        { lat: 10.6700, lng: -63.2500 }  // Vuelta al inicio
    ]
}

// Índices de ruta para cada vehículo
const routeIndices = ref<Record<number, number>>({
    1: 0, 2: 0, 3: 0, 4: 0
})

// Simulación de movimiento de vehículos por rutas predefinidas
const simulateVehicleMovement = () => {
    vehicles.value.forEach(vehicle => {
        if (vehicle.status === 'activo' && vehicle.speed > 0) {
            const route = vehicleRoutes[vehicle.id as keyof typeof vehicleRoutes]
            if (!route || route.length === 0) return

            // Obtener siguiente punto en la ruta
            const currentIndex = routeIndices.value[vehicle.id]
            const nextIndex = (currentIndex + 1) % route.length
            const targetPoint = route[nextIndex]

            // Calcular movimiento gradual hacia el siguiente punto
            const currentLat = vehicle.current_location.lat
            const currentLng = vehicle.current_location.lng
            const targetLat = targetPoint.lat
            const targetLng = targetPoint.lng

            // Velocidad de movimiento (más lento = más realista)
            const moveSpeed = 0.0002 // ~20 metros por actualización

            // Calcular dirección
            const latDiff = targetLat - currentLat
            const lngDiff = targetLng - currentLng
            const distance = Math.sqrt(latDiff * latDiff + lngDiff * lngDiff)

            if (distance < moveSpeed) {
                // Llegó al punto, avanzar al siguiente
                vehicle.current_location = { ...targetPoint }
                routeIndices.value[vehicle.id] = nextIndex

                // Simular parada ocasional en puntos de recolección
                if (Math.random() < 0.3) { // 30% probabilidad de parar
                    vehicle.speed = 0
                    setTimeout(() => {
                        if (vehicle.status === 'activo') {
                            const baseSpeed = vehicle.id === 1 ? 25 : vehicle.id === 2 ? 30 : 22
                            vehicle.speed = baseSpeed + (Math.random() - 0.5) * 8
                        }
                    }, 15000) // Parar por 15 segundos
                }
            } else {
                // Moverse gradualmente hacia el objetivo
                const moveRatio = moveSpeed / distance
                vehicle.current_location.lat += latDiff * moveRatio
                vehicle.current_location.lng += lngDiff * moveRatio

                // Ajustar velocidad según el movimiento
                const baseSpeed = vehicle.id === 1 ? 25 : vehicle.id === 2 ? 30 : 22
                vehicle.speed = Math.max(15, Math.min(35, baseSpeed + (Math.random() - 0.5) * 5))
            }

            vehicle.updated_at = new Date().toISOString()
        }
    })

    updateVehicleMarkers()
    updateLastUpdate()
}

// Lifecycle hooks
onMounted(() => {
    initializeMap()

    // Exponer funciones al objeto window para uso en popups
    window.viewVehicleDetails = viewVehicleDetails
    window.showRoutePopup = showRoutePopup
    window.hideRoutePopup = hideRoutePopup

    // Configurar actualización automática con movimiento
    const updateInterval = setInterval(() => {
        if (autoUpdate.value) {
            simulateVehicleMovement()
        }
    }, 5000) // Cada 5 segundos para movimiento más fluido y realista

    // Limpiar intervalo al desmontar
    onUnmounted(() => {
        clearInterval(updateInterval)

        // Limpiar todas las rutas completamente
        routingControls.value.forEach((control, routeId) => {
            clearVehicleCompletely(routeId)
        })

        // Limpiar funciones del objeto window
        delete window.viewVehicleDetails
        delete window.showRoutePopup
        delete window.hideRoutePopup
    })
})
</script>

<style scoped>
.live-map-container {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    border: 1px solid rgb(229 231 235);
    overflow: hidden;
}

.map-wrapper {
    position: relative;
}

#live-map {
    width: 100%;
}

/* Estilos para los popups de Leaflet */
:deep(.leaflet-popup-content-wrapper) {
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

:deep(.leaflet-popup-content) {
    font-size: 0.875rem;
    margin: 8px 12px;
}

/* Estilos para iconos personalizados */
:deep(.custom-vehicle-icon) {
    background: none !important;
    border: none !important;
}

:deep(.leaflet-popup-content button) {
    transition: background-color 0.2s;
}

:deep(.leaflet-popup-content button:hover) {
    background-color: #2563eb !important;
}

/* Estilos para ventanas emergentes de rutas */
.route-popup {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>
