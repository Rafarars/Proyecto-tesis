<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Sistema de Geolocalización - Carúpano
                </h1>
                <p class="mt-2 text-lg text-gray-600">
                    Demostración del sistema de optimización de rutas de recolección de desechos
                </p>
            </div>

            <!-- Fleet Status -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Estado de la Flota</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ fleetStatus.total_vehicles }}</div>
                        <div class="text-sm text-blue-800">Total Vehículos</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ fleetStatus.active_vehicles }}</div>
                        <div class="text-sm text-green-800">Activos</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ fleetStatus.maintenance_vehicles }}</div>
                        <div class="text-sm text-yellow-800">En Mantenimiento</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ totalCapacity.toFixed(1) }}</div>
                        <div class="text-sm text-purple-800">Capacidad Total (m³)</div>
                    </div>
                </div>

                <!-- Fleet Center -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Centro Geográfico de la Flota</h3>
                    <p class="text-sm text-gray-600">
                        Latitud: {{ fleetStatus.fleet_center?.latitude }}, 
                        Longitud: {{ fleetStatus.fleet_center?.longitude }}
                    </p>
                </div>
            </div>

            <!-- Vehicle List -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehículos en Tiempo Real</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vehículo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ubicación
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Última Actualización
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="vehicle in vehicles" :key="vehicle.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <TruckIcon class="h-6 w-6 text-blue-600" />
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ vehicle.license_plate }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ vehicle.brand }} {{ vehicle.model }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ vehicle.location.latitude.toFixed(4) }}, {{ vehicle.location.longitude.toFixed(4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(vehicle.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ vehicle.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatTime(vehicle.last_update) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button 
                                        @click="simulateMovement(vehicle)"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Simular Movimiento
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Spatial Search Demo -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Búsqueda Espacial</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Latitud</label>
                        <input 
                            v-model="searchParams.latitude" 
                            type="number" 
                            step="0.0001"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Longitud</label>
                        <input 
                            v-model="searchParams.longitude" 
                            type="number" 
                            step="0.0001"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Radio (metros)</label>
                        <input 
                            v-model="searchParams.radius" 
                            type="number" 
                            min="100"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                </div>
                <button 
                    @click="searchNearbyVehicles"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700"
                >
                    Buscar Vehículos Cercanos
                </button>

                <!-- Search Results -->
                <div v-if="searchResults" class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">
                        Resultados: {{ searchResults.vehicles_found }} vehículos encontrados
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div v-for="vehicle in searchResults.vehicles" :key="vehicle.id" class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                            <span class="font-medium">{{ vehicle.license_plate }}</span>
                            <span class="text-sm text-gray-600">{{ vehicle.distance_meters }}m de distancia</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Acciones del Sistema</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button 
                        @click="refreshFleetStatus"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                    >
                        Actualizar Estado
                    </button>
                    <button 
                        @click="simulateAllMovements"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                    >
                        Simular Movimiento de Toda la Flota
                    </button>
                    <button 
                        @click="resetToCarupano"
                        class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700"
                    >
                        Resetear a Carúpano
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { TruckIcon } from '@heroicons/vue/24/outline'

// Reactive data
const vehicles = ref([])
const fleetStatus = ref({})
const searchParams = ref({
    latitude: 10.6678,
    longitude: -63.2583,
    radius: 1000
})
const searchResults = ref(null)

// Computed properties
const totalCapacity = computed(() => {
    return vehicles.value.reduce((total, vehicle) => total + parseFloat(vehicle.capacity), 0)
})

// Methods
const refreshFleetStatus = async () => {
    try {
        const response = await fetch('/test-fleet-status', {
            headers: { 'Accept': 'application/json' }
        })
        const data = await response.json()
        fleetStatus.value = data.fleet_summary
        vehicles.value = data.vehicles
    } catch (error) {
        console.error('Error fetching fleet status:', error)
    }
}

const searchNearbyVehicles = async () => {
    try {
        const url = `/test-nearby?latitude=${searchParams.value.latitude}&longitude=${searchParams.value.longitude}&radius=${searchParams.value.radius}`
        const response = await fetch(url, {
            headers: { 'Accept': 'application/json' }
        })
        searchResults.value = await response.json()
    } catch (error) {
        console.error('Error searching nearby vehicles:', error)
    }
}

const simulateMovement = async (vehicle) => {
    // Generate random movement within Carúpano area
    const baseLat = 10.6678
    const baseLng = -63.2583
    const randomLat = baseLat + (Math.random() - 0.5) * 0.01
    const randomLng = baseLng + (Math.random() - 0.5) * 0.01
    
    try {
        const url = `/test-update-location-get/${vehicle.id}?latitude=${randomLat}&longitude=${randomLng}`
        await fetch(url, {
            headers: { 'Accept': 'application/json' }
        })
        await refreshFleetStatus()
    } catch (error) {
        console.error('Error simulating movement:', error)
    }
}

const simulateAllMovements = async () => {
    for (const vehicle of vehicles.value) {
        if (vehicle.status === 'active') {
            await simulateMovement(vehicle)
            // Small delay between movements
            await new Promise(resolve => setTimeout(resolve, 500))
        }
    }
}

const resetToCarupano = async () => {
    const carupanoPositions = [
        { lat: 10.6678, lng: -63.2583 },
        { lat: 10.67, lng: -63.26 },
        { lat: 10.665, lng: -63.255 },
        { lat: 10.669, lng: -63.257 }
    ]
    
    for (let i = 0; i < vehicles.value.length; i++) {
        const vehicle = vehicles.value[i]
        const position = carupanoPositions[i]
        if (position) {
            try {
                const url = `/test-update-location-get/${vehicle.id}?latitude=${position.lat}&longitude=${position.lng}`
                await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                })
            } catch (error) {
                console.error('Error resetting vehicle position:', error)
            }
        }
    }
    await refreshFleetStatus()
}

const getStatusClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800'
        case 'maintenance':
            return 'bg-yellow-100 text-yellow-800'
        case 'inactive':
            return 'bg-red-100 text-red-800'
        default:
            return 'bg-gray-100 text-gray-800'
    }
}

const formatTime = (timestamp) => {
    return new Date(timestamp).toLocaleString('es-ES')
}

// Initialize
onMounted(() => {
    refreshFleetStatus()
})
</script>
