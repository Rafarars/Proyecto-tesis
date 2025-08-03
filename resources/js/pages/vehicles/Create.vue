<template>
    <AppLayout title="Crear Vehículo" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nuevo Vehículo</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo vehículo en la flota de recolección</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
            <!-- Información Básica -->
            <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Básica</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Datos principales del vehículo</p>
            </div>
            <form @submit.prevent="submit" class="space-y-6">
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Placa *
                            </label>
                            <input
                                v-model="form.license_plate"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: ABC-123"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Marca *
                            </label>
                            <input
                                v-model="form.brand"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: Chevrolet"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Modelo *
                            </label>
                            <input
                                v-model="form.model"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: NPR"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Año *
                            </label>
                            <input
                                v-model="form.year"
                                type="number"
                                required
                                min="1990"
                                max="2030"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 2020"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Color *
                            </label>
                            <input
                                v-model="form.color"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: Blanco"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Vehículo *
                            </label>
                            <select
                                v-model="form.type"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar tipo</option>
                                <option value="compactador">Compactador</option>
                                <option value="camion">Camión</option>
                                <option value="pickup">Pickup</option>
                                <option value="van">Van</option>
                                <option value="volqueta">Volqueta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Especificaciones Técnicas -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Especificaciones Técnicas</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Características técnicas del vehículo</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Capacidad de Carga (toneladas) *
                            </label>
                            <input
                                v-model="form.capacity_tons"
                                type="number"
                                step="0.1"
                                min="0.1"
                                max="50"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 5.0"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Capacidad de Combustible (litros) *
                            </label>
                            <input
                                v-model="form.fuel_capacity_liters"
                                type="number"
                                min="10"
                                max="1000"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 100"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Combustible *
                            </label>
                            <select
                                v-model="form.fuel_type"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar combustible</option>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                                <option value="gas">Gas</option>
                                <option value="electrico">Eléctrico</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Estado Inicial</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estado inicial y kilometraje del vehículo</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado *
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar estado</option>
                                <option value="activo">Activo</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="fuera_servicio">Fuera de Servicio</option>
                                <option value="reparacion">Reparación</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kilometraje Inicial (km)
                            </label>
                            <input
                                v-model="form.odometer_km"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 0 (opcional)"
                            >
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="p-6 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex justify-end space-x-4">
                    <Link
                        href="/vehicles"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                    >
                        {{ processing ? 'Guardando...' : 'Crear Vehículo' }}
                    </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Gestión de Vehículos',
        href: '/vehicles',
    },
    {
        title: 'Crear Vehículo',
        href: '/vehicles/create',
        current: true
    }
]

// Formulario
const form = useForm({
    license_plate: '',
    brand: '',
    model: '',
    year: new Date().getFullYear(),
    color: '',
    type: '',
    capacity_tons: '',
    fuel_capacity_liters: '',
    fuel_type: '',
    status: 'activo',
    odometer_km: 0
})

const processing = ref(false)

const submit = () => {
    processing.value = true
    form.post('/vehicles', {
        onSuccess: () => {
            // Redirigir a la lista de vehículos
        },
        onError: () => {
            processing.value = false
        },
        onFinish: () => {
            processing.value = false
        }
    })
}
</script>
