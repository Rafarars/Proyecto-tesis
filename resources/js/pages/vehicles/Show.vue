<template>
  <AppLayout title="Detalles del Vehículo" :breadcrumbs="breadcrumbs">
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
            Detalles del Vehículo
          </h2>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
            {{ vehicle.code }} - {{ vehicle.license_plate }}
          </p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('vehicles.edit', vehicle.id)"
            class="h-11 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Editar
          </Link>
          <Link
            :href="route('vehicles.index')"
            class="h-11 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center shadow-sm"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16v6 M4 16v6"></path>
              </svg>
            </div>
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles del Vehículo</h1>
              <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa del vehículo {{ vehicle.code }}</p>
            </div>
            <div class="flex-shrink-0">
              <Link
                :href="route('vehicles.index')"
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información Básica</h3>
              </div>
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Placa</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.license_plate }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Código</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.code }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Marca</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.brand }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Modelo</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.model }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Año</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.year }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Color</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.color }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ getVehicleTypeText(vehicle.type) }}</p>
                  </div>
                  <div v-if="vehicle.vin" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">VIN</label>
                    <p class="mt-1 text-base font-mono font-semibold text-gray-900 dark:text-white">{{ vehicle.vin }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Especificaciones técnicas -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Especificaciones Técnicas</h3>
              </div>
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Capacidad</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.capacity_tons }} toneladas</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Combustible</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ getFuelTypeText(vehicle.fuel_type) }}</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Capacidad del Tanque</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.fuel_capacity_liters }} litros</p>
                  </div>
                  <div v-if="vehicle.engine" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Motor</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.engine }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información operacional -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-yellow-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información Operacional</h3>
              </div>
              <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Kilometraje</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ formatNumber(vehicle.odometer_km) }} km</p>
                  </div>
                  <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Velocidad Actual</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.current_speed }} km/h</p>
                  </div>
                  <div v-if="vehicle.last_maintenance" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Último Mantenimiento</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ formatDate(vehicle.last_maintenance) }}</p>
                  </div>
                  <div v-if="vehicle.next_maintenance" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Próximo Mantenimiento</label>
                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ formatDate(vehicle.next_maintenance) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Panel lateral -->
          <div class="space-y-4">
            <!-- Estado y alertas -->
            <div class="flex flex-wrap gap-3 justify-center">
              <!-- Estado del vehículo -->
              <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-lg shadow-sm"
                    :class="getStatusColor(vehicle.status)">
                {{ getStatusText(vehicle.status) }}
              </span>

              <!-- Estado de mantenimiento -->
              <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-lg shadow-sm"
                    :class="getMaintenanceStatusColor(vehicle.maintenance_status)">
                Mantenimiento: {{ getMaintenanceStatusText(vehicle.maintenance_status) }}
              </span>
            </div>

            <!-- Conductor asignado -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Conductor Asignado</h3>
              </div>
              <div class="p-4">
                <div v-if="vehicle.driver" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                      <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 flex items-center justify-center shadow-md">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-base font-semibold text-gray-900 dark:text-white">{{ vehicle.driver.full_name }}</div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">{{ vehicle.driver.employee_code }}</div>
                    </div>
                  </div>
                  <div class="mt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Teléfono:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.driver.phone || 'No disponible' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Licencia:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.driver.license_number }}</span>
                    </div>
                  </div>
                  <div class="mt-4">
                    <button
                      @click="showUnassignModal = true"
                      class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>
                      Desasignar Conductor
                    </button>
                  </div>
                </div>
                <div v-else class="text-center py-8 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                  <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 mb-3">Sin conductor asignado</p>
                  <button
                    @click="showAssignModal = true"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center mx-auto"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Asignar Conductor
                  </button>
                </div>
              </div>
            </div>

            <!-- Ubicación actual -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-cyan-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ubicación Actual</h3>
              </div>
              <div class="p-4">
                <div v-if="vehicle.current_location" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                  <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Latitud:</span>
                      <span class="text-gray-900 dark:text-white font-mono font-medium">{{ vehicle.current_location.lat }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Longitud:</span>
                      <span class="text-gray-900 dark:text-white font-mono font-medium">{{ vehicle.current_location.lng }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Velocidad:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.current_speed }} km/h</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Actualizado:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ formatDate(vehicle.location_updated_at) }}</span>
                    </div>
                  </div>
                  <div class="mt-4">
                    <Link
                      :href="route('mapa.vivo', { vehicle_id: vehicle.id })"
                      class="w-full h-11 bg-cyan-600 hover:bg-cyan-700 dark:bg-cyan-600 dark:hover:bg-cyan-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center justify-center"
                    >
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      Ver en Mapa
                    </Link>
                  </div>
                </div>
                <div v-else class="text-center py-8 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                  <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sin ubicación disponible</p>
                </div>
              </div>
            </div>

            <!-- Ruta Asignada -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
              <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ruta Asignada</h3>
              </div>
              <div class="p-4">
                <div v-if="vehicle.assigned_route" class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                  <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Nombre:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.assigned_route.name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Código:</span>
                      <span class="text-gray-900 dark:text-white font-mono font-medium">{{ vehicle.assigned_route.code }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Estado:</span>
                      <span :class="getRouteStatusClass(vehicle.assigned_route.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ getRouteStatusText(vehicle.assigned_route.status) }}
                      </span>
                    </div>
                    <div class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Puntos:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.assigned_route.collection_points_count }}</span>
                    </div>
                    <div v-if="vehicle.assigned_route.total_distance_km" class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Distancia:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.assigned_route.total_distance_km }} km</span>
                    </div>
                    <div v-if="vehicle.assigned_route.estimated_duration_formatted" class="flex justify-between text-sm">
                      <span class="text-gray-500 dark:text-gray-400">Duración:</span>
                      <span class="text-gray-900 dark:text-white font-medium">{{ vehicle.assigned_route.estimated_duration_formatted }}</span>
                    </div>
                  </div>
                  <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <Link
                      :href="route('routes.show', vehicle.assigned_route.id)"
                      class="h-11 bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center justify-center"
                    >
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                      </svg>
                      Ver Ruta
                    </Link>
                    <Link
                      :href="`/mapa-vivo?vehicle_id=${vehicle.id}`"
                      class="h-11 bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center justify-center"
                    >
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      Seguir en Mapa
                    </Link>
                  </div>
                </div>
                <div v-else class="text-center py-8 bg-gray-50 dark:bg-slate-700/50 rounded-lg">
                  <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sin ruta asignada</p>
                  <div class="mt-4">
                    <Link
                      :href="route('routes.index')"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                      Asignar Ruta
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <!-- Modal para asignar conductor -->
    <div v-if="showAssignModal" class="fixed inset-0 z-[9999] overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white dark:bg-slate-800 rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
          Asignar Conductor a {{ vehicle.license_plate }}
        </h3>

        <!-- Debug info -->
        <div class="mb-4 p-2 bg-yellow-100 rounded text-sm">
          <p>Conductores disponibles: {{ availableDrivers.length }}</p>
          <p v-if="availableDrivers.length > 0">Primer conductor: {{ availableDrivers[0].first_name }} {{ availableDrivers[0].last_name }}</p>
        </div>

        <form @submit.prevent="assignDriver">
          <div class="mb-4">
            <label for="driver_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Seleccionar Conductor
            </label>
            <select
              id="driver_id"
              v-model="assignForm.driver_id"
              required
              class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 transition-colors"
            >
              <option value="">Seleccionar conductor...</option>
              <option
                v-for="driver in availableDrivers"
                :key="driver.id"
                :value="driver.id"
              >
                {{ driver.first_name }} {{ driver.last_name }} - {{ driver.employee_code }} ({{ driver.license_type }})
              </option>
            </select>
            <p v-if="availableDrivers.length === 0" class="mt-2 text-sm text-red-500">
              No hay conductores disponibles para asignar.
            </p>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="showAssignModal = false"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
            >
              Cancelar
            </button>
            <button
              type="submit"
              :disabled="assignForm.processing || !assignForm.driver_id"
              class="bg-purple-600 hover:bg-purple-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
            >
              {{ assignForm.processing ? 'Asignando...' : 'Asignar Conductor' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal para desasignar conductor -->
    <div v-if="showUnassignModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showUnassignModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white dark:bg-slate-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                  Desasignar Conductor
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    ¿Estás seguro de que quieres desasignar al conductor {{ vehicle.driver?.full_name }} del vehículo {{ vehicle.license_plate }}?
                    <br><br>
                    El conductor quedará disponible para ser asignado a otro vehículo.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="unassignDriver"
              :disabled="unassignForm.processing"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
            >
              {{ unassignForm.processing ? 'Desasignando...' : 'Desasignar' }}
            </button>
            <button
              type="button"
              @click="showUnassignModal = false"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-slate-600 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-slate-500"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'

// Props del componente
interface Props {
  vehicle: {
    id: number
    license_plate: string
    code: string
    brand: string
    model: string
    year: number
    color: string
    type: string
    capacity_tons: number
    fuel_capacity_liters: number
    fuel_type: string
    engine?: string
    status: string
    driver?: {
      id: number
      full_name: string
      employee_code: string
      phone?: string
      license_number?: string
    }
    current_location?: {
      lat: number
      lng: number
    }
    current_speed: number
    odometer_km: number
    last_maintenance?: string
    next_maintenance?: string
    maintenance_status: string
    location_updated_at?: string
    vin?: string
    assigned_route?: {
      id: number
      name: string
      code: string
      status: string
      collection_points_count: number
      total_distance_km: number | null
      estimated_duration_formatted: string
    }
  }
  availableDrivers: Array<{
    id: number
    first_name: string
    last_name: string
    employee_code: string
    license_number: string
    license_type: string
  }>
}

const props = defineProps<Props>()

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Gestión de Vehículos',
    href: '/vehicles',
  },
  {
    title: 'Detalles del Vehículo',
    href: `/vehicles/${props.vehicle.id}`,
    current: true
  }
]

// Métodos helper
const getStatusColor = (status: string) => {
  const colors = {
    activo: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700',
    mantenimiento: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700',
    fuera_servicio: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700',
    reparacion: 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-700'
  }
  return colors[status as keyof typeof colors] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
}

const getStatusText = (status: string) => {
  const texts = {
    activo: 'Activo',
    mantenimiento: 'Mantenimiento',
    fuera_servicio: 'Fuera de Servicio',
    reparacion: 'Reparación'
  }
  return texts[status as keyof typeof texts] || status
}

const getMaintenanceStatusColor = (status: string) => {
  const colors = {
    al_dia: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700',
    proximo: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700',
    vencido: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-700',
    sin_programar: 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
  }
  return colors[status as keyof typeof colors] || 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700'
}

const getMaintenanceStatusText = (status: string) => {
  const texts = {
    al_dia: 'Al día',
    proximo: 'Próximo',
    vencido: 'Vencido',
    sin_programar: 'Sin programar'
  }
  return texts[status as keyof typeof texts] || status
}

const getVehicleTypeText = (type: string) => {
  const texts = {
    compactador: 'Compactador',
    camion: 'Camión',
    pickup: 'Pickup',
    van: 'Van',
    volqueta: 'Volqueta'
  }
  return texts[type as keyof typeof texts] || type
}

const getRouteStatusText = (status: string) => {
  const statusTexts = {
    activa: 'Activa',
    inactiva: 'Inactiva',
    en_planificacion: 'En Planificación',
    completada: 'Completada'
  }
  return statusTexts[status as keyof typeof statusTexts] || status
}

const getRouteStatusClass = (status: string) => {
  const classes = {
    activa: 'bg-green-100 text-green-800',
    inactiva: 'bg-gray-100 text-gray-800',
    en_planificacion: 'bg-yellow-100 text-yellow-800',
    completada: 'bg-blue-100 text-blue-800'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

const getFuelTypeText = (type: string) => {
  const texts = {
    gasolina: 'Gasolina',
    diesel: 'Diésel',
    gas: 'Gas',
    electrico: 'Eléctrico'
  }
  return texts[type as keyof typeof texts] || type
}

const formatDate = (date?: string) => {
  if (!date) return ''
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatNumber = (number?: number) => {
  if (!number) return '0'
  return new Intl.NumberFormat('es-ES', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2
  }).format(number)
}

// Variables reactivas para modales
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const showAssignModal = ref(false)
const showUnassignModal = ref(false)

// Formularios
const assignForm = useForm({
  driver_id: ''
})

const unassignForm = useForm({})

// Funciones de asignación
const assignDriver = () => {
  assignForm.post(route('vehicles.assign-driver', props.vehicle.id), {
    onSuccess: () => {
      showAssignModal.value = false
      assignForm.reset()
    },
    onError: (errors) => {
      console.error('Error assigning driver:', errors)
      showAssignModal.value = false
    },
    onFinish: () => {
      // Asegurar que el modal se cierre siempre
      showAssignModal.value = false
    }
  })
}

const unassignDriver = () => {
  unassignForm.delete(route('vehicles.unassign-driver', props.vehicle.id), {
    onSuccess: () => {
      showUnassignModal.value = false
    },
    onError: (errors) => {
      console.error('Error unassigning driver:', errors)
      showUnassignModal.value = false
    },
    onFinish: () => {
      // Asegurar que el modal se cierre siempre
      showUnassignModal.value = false
    }
  })
}
</script>
