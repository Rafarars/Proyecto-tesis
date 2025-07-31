<template>
  <AppLayout title="Gestión de Vehículos">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Sistema de Geolocalización - Vehículos
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            
            <!-- Estadísticas rápidas -->
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
              <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/20">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <TruckIcon class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Vehículos</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ vehicles.length }}</p>
                  </div>
                </div>
              </div>
              
              <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <CheckCircleIcon class="h-8 w-8 text-green-600 dark:text-green-400" />
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Activos</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ activeVehicles }}</p>
                  </div>
                </div>
              </div>
              
              <div class="rounded-lg bg-yellow-50 p-4 dark:bg-yellow-900/20">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <WrenchScrewdriverIcon class="h-8 w-8 text-yellow-600 dark:text-yellow-400" />
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Mantenimiento</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ maintenanceVehicles }}</p>
                  </div>
                </div>
              </div>
              
              <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <XCircleIcon class="h-8 w-8 text-red-600 dark:text-red-400" />
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Inactivos</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ inactiveVehicles }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabla de vehículos -->
            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Vehículo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Tipo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Capacidad
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Ubicación
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                  <tr v-for="vehicle in vehicles" :key="vehicle.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0">
                          <component :is="getVehicleIcon(vehicle.type)" class="h-8 w-8 text-gray-400" />
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ vehicle.license_plate }}
                          </div>
                          <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ vehicle.brand }} {{ vehicle.model }} ({{ vehicle.year }})
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                            :class="getTypeClass(vehicle.type)">
                        {{ getTypeLabel(vehicle.type) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                      {{ vehicle.capacity }} ton
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                            :class="getStatusClass(vehicle.status)">
                        {{ getStatusLabel(vehicle.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                      <div class="flex items-center">
                        <MapPinIcon class="mr-1 h-4 w-4" />
                        {{ vehicle.latitude.toFixed(4) }}, {{ vehicle.longitude.toFixed(4) }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <Link :href="route('vehicles.show', vehicle.id)" 
                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        Ver detalles
                      </Link>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { 
  TruckIcon, 
  CheckCircleIcon, 
  WrenchScrewdriverIcon, 
  XCircleIcon,
  MapPinIcon
} from '@heroicons/vue/24/outline'

interface Vehicle {
  id: number
  license_plate: string
  brand: string
  model: string
  year: number
  type: 'truck' | 'compactor' | 'pickup' | 'van'
  capacity: number
  status: 'active' | 'maintenance' | 'inactive'
  latitude: number
  longitude: number
  created_at: string
  updated_at: string
}

interface Props {
  vehicles: Vehicle[]
}

const props = defineProps<Props>()

const activeVehicles = computed(() => 
  props.vehicles.filter(v => v.status === 'active').length
)

const maintenanceVehicles = computed(() => 
  props.vehicles.filter(v => v.status === 'maintenance').length
)

const inactiveVehicles = computed(() => 
  props.vehicles.filter(v => v.status === 'inactive').length
)

const getVehicleIcon = (type: string) => {
  return TruckIcon // Por simplicidad, usamos el mismo icono para todos
}

const getTypeClass = (type: string) => {
  const classes = {
    truck: 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
    compactor: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
    pickup: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
    van: 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400'
  }
  return classes[type as keyof typeof classes] || classes.truck
}

const getTypeLabel = (type: string) => {
  const labels = {
    truck: 'Camión',
    compactor: 'Compactador',
    pickup: 'Pickup',
    van: 'Furgoneta'
  }
  return labels[type as keyof typeof labels] || type
}

const getStatusClass = (status: string) => {
  const classes = {
    active: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
    maintenance: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
    inactive: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
  }
  return classes[status as keyof typeof classes] || classes.inactive
}

const getStatusLabel = (status: string) => {
  const labels = {
    active: 'Activo',
    maintenance: 'Mantenimiento',
    inactive: 'Inactivo'
  }
  return labels[status as keyof typeof labels] || status
}
</script>
