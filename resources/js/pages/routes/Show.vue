<template>
  <AppLayout title="Detalles de la Ruta" :breadcrumbs="breadcrumbs">
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
            Detalles de la Ruta
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ routeData.code }} - {{ routeData.name }}
          </p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('routes.edit', routeData.id)"
            class="h-11 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
          </Link>
        </div>
      </div>
    </template>

    <div class="mb-6">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-6 border border-emerald-200 dark:border-emerald-800">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
              <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
              </svg>
            </div>
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles de la Ruta</h1>
              <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa de la ruta {{ routeData.code }}</p>
            </div>
            <div class="flex-shrink-0">
              <Link
                :href="route('routes.index')"
                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
              </Link>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Información principal -->
          <div class="lg:col-span-2 space-y-4">
            <!-- Información básica -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-emerald-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información de la Ruta</h3>
              </div>
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ getStatusText(routeData.status) }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ getTypeText(routeData.type) }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Puntos de Recolección</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.collection_points_count }} puntos</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Distancia Total</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.total_distance_km || 'No calculada' }} km</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Duración Estimada</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.estimated_duration_formatted }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Días Programados</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.formatted_days }}</p>
                  </div>
                  <div v-if="routeData.start_time" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Horario</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.start_time }} - {{ routeData.end_time }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Creado por</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.creator.name }}</p>
                  </div>
                </div>
                <div v-if="routeData.description" class="mt-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ routeData.description }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Puntos de Recolección -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Puntos de Recolección</h3>
              </div>
              <div class="p-4">
                <div class="space-y-3">
                  <div
                    v-for="(point, index) in routeData.collection_points"
                    :key="index"
                    class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600"
                  >
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Punto {{ index + 1 }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ point.address }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                          Coordenadas: {{ point.lat }}, {{ point.lng }}
                        </p>
                      </div>
                      <div class="ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                          Prioridad {{ point.priority || index + 1 }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Columna lateral -->
          <div class="space-y-4">
            <!-- Estado y alertas -->
            <div class="flex flex-wrap gap-3 justify-center">
              <!-- Estado de la ruta -->
              <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-lg shadow-sm"
                    :class="getStatusColor(routeData.status)">
                {{ getStatusText(routeData.status) }}
              </span>

              <!-- Tipo de ruta -->
              <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-lg shadow-sm"
                    :class="getTypeColor(routeData.type)">
                {{ getTypeText(routeData.type) }}
              </span>
            </div>

            <!-- Vehículo Asignado -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Vehículo Asignado</h3>
              </div>
              <div class="p-4">
                <div v-if="routeData.assigned_vehicle" class="space-y-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                      <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ routeData.assigned_vehicle.license_plate }}</h4>
                      <span class="text-xs text-gray-500 dark:text-gray-400">{{ routeData.assigned_vehicle.code }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ routeData.assigned_vehicle.type }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                      Capacidad: {{ routeData.assigned_vehicle.capacity_tons }} toneladas
                    </p>
                    <div class="mt-3 flex space-x-2">
                      <Link
                        :href="route('vehicles.show', routeData.assigned_vehicle.id)"
                        class="text-xs text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                      >
                        Ver Detalles
                      </Link>
                      <Link
                        :href="`/mapa-vivo?vehicle_id=${routeData.assigned_vehicle.id}`"
                        class="text-xs text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                      >
                        Ver en Mapa
                      </Link>
                    </div>
                  </div>

                  <div v-if="routeData.assigned_at" class="text-xs text-gray-500 dark:text-gray-400">
                    Asignado el {{ routeData.assigned_at }}
                  </div>
                </div>

                <div v-else class="text-center py-6">
                  <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0M15 17a2 2 0 104 0"></path>
                  </svg>
                  <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Sin vehículo asignado</h3>
                  <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Esta ruta no tiene un vehículo asignado.</p>
                </div>
              </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-orange-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Estadísticas</h3>
              </div>
              <div class="p-4">
                <div class="space-y-3">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Creada</span>
                      <span class="text-sm font-medium text-gray-900 dark:text-white">{{ routeData.created_at }}</span>
                    </div>
                  </div>

                  <div v-if="routeData.last_optimized_at" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Última optimización</span>
                      <span class="text-sm font-medium text-gray-900 dark:text-white">{{ routeData.last_optimized_at }}</span>
                    </div>
                  </div>

                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Puntos</span>
                      <span class="text-sm font-medium text-gray-900 dark:text-white">{{ routeData.collection_points_count }}</span>
                    </div>
                  </div>

                  <div v-if="routeData.total_distance_km" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Distancia</span>
                      <span class="text-sm font-medium text-gray-900 dark:text-white">{{ routeData.total_distance_km }} km</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-indigo-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Acciones</h3>
              </div>
              <div class="p-4">
                <div class="space-y-3">
                  <div class="space-y-2">
                    <button
                      @click="optimizeRoute"
                      :disabled="optimizing"
                      class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 transition duration-200 shadow-sm"
                    >
                      <svg v-if="optimizing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                      </svg>
                      <span v-if="optimizing">Optimizando...</span>
                      <span v-else>Optimizar Ruta</span>
                    </button>

                    <!-- Mensaje de optimización -->
                    <div v-if="showMessage" class="p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                      <p class="text-xs text-blue-700 dark:text-blue-300 text-center">{{ optimizationMessage }}</p>
                    </div>

                    <div v-if="routeData.last_optimized_at" class="text-xs text-gray-500 dark:text-gray-400 text-center">
                      Última optimización: {{ routeData.last_optimized_at }}
                    </div>
                    <div v-else class="text-xs text-gray-500 dark:text-gray-400 text-center">
                      Esta ruta no ha sido optimizada
                    </div>
                  </div>

                  <Link
                    :href="`/mapa-vivo?route_id=${routeData.id}`"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 shadow-sm"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ver en Mapa
                  </Link>

                  <Link
                    :href="route('routes.edit', routeData.id)"
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200 shadow-sm"
                  >
                    Editar Ruta
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'

interface RouteData {
    id: number
    name: string
    code: string
    description: string
    status: string
    type: string
    collection_points: Array<{
        lat: number
        lng: number
        address: string
        priority?: number
    }>
    optimized_sequence: any[]
    total_distance_km: number | null
    estimated_duration_minutes: number | null
    estimated_duration_formatted: string
    start_time: string | null
    end_time: string | null
    days_of_week: number[]
    formatted_days: string
    start_lat: number | null
    start_lng: number | null
    start_address: string | null
    end_lat: number | null
    end_lng: number | null
    end_address: string | null
    is_assigned: boolean
    assigned_vehicle: {
        id: number
        license_plate: string
        code: string
        type: string
        capacity_tons: number
        status: string
        current_location: any
    } | null
    creator: {
        name: string
    }
    status_badge_color: string
    collection_points_count: number
    created_at: string
    assigned_at: string | null
    last_optimized_at: string | null
}

interface Props {
    route: RouteData
}

const props = defineProps<Props>()

// Rename to avoid conflict with route() helper function
const routeData = props.route

const optimizing = ref(false)
const optimizationMessage = ref('')
const showMessage = ref(false)

// Breadcrumbs
const breadcrumbs = [
  {
    title: 'Gestión de Rutas',
    href: '/routes',
  },
  {
    title: 'Detalles de la Ruta',
    href: `/routes/${routeData.id}`,
    current: true
  }
]

// Helper functions
const getStatusColor = (status: string) => {
    const colors = {
        activa: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
        inactiva: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        en_planificacion: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        completada: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
    }
    return colors[status as keyof typeof colors] || colors.inactiva
}

const getTypeColor = (type: string) => {
    const colors = {
        recoleccion_residencial: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        recoleccion_comercial: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        recoleccion_mixta: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
    }
    return colors[type as keyof typeof colors] || colors.recoleccion_mixta
}

const getStatusText = (status: string) => {
    const statusTexts = {
        activa: 'Activa',
        inactiva: 'Inactiva',
        en_planificacion: 'En Planificación',
        completada: 'Completada'
    }
    return statusTexts[status as keyof typeof statusTexts] || status
}

const getTypeText = (type: string) => {
    const typeTexts = {
        recoleccion_residencial: 'Recolección Residencial',
        recoleccion_comercial: 'Recolección Comercial',
        recoleccion_mixta: 'Recolección Mixta'
    }
    return typeTexts[type as keyof typeof typeTexts] || type
}

const optimizeRoute = () => {
    optimizing.value = true
    showMessage.value = false

    router.post(route('routes.optimize', routeData.id), {}, {
        onSuccess: (page) => {
            // Mostrar mensaje de éxito
            optimizationMessage.value = '✅ Ruta optimizada exitosamente. Recargando datos...'
            showMessage.value = true

            // Recargar la página para mostrar los datos actualizados
            setTimeout(() => {
                router.reload({
                    onSuccess: () => {
                        optimizationMessage.value = '🎯 Optimización completada. Distancia y duración actualizadas.'
                        setTimeout(() => {
                            showMessage.value = false
                        }, 3000)
                    }
                })
            }, 1000)
        },
        onError: (errors) => {
            optimizationMessage.value = '❌ Error al optimizar la ruta. Inténtalo de nuevo.'
            showMessage.value = true
            setTimeout(() => {
                showMessage.value = false
            }, 5000)
        },
        onFinish: () => {
            optimizing.value = false
        }
    })
}
</script>
