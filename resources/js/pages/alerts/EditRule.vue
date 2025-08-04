<template>
    <AppLayout title="Editar Regla" :breadcrumbs="breadcrumbs">
        <Head title="Editar Regla de Alerta" />

        <!-- Header -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Regla</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Modifica la configuración de la regla de detección automática</p>
                </div>
                <Link
                    :href="route('alert-rules.show', alertRule.id)"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Volver
                </Link>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Información Básica -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Básica</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Configura los datos principales de la regla</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nombre de la Regla</label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: Desvío de Ruta Crítico"
                                required
                            />
                            <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Código de la Regla</label>
                            <div class="w-full h-11 px-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-slate-600 text-gray-900 dark:text-white shadow-sm flex items-center">
                                <span class="font-mono">{{ alertRule.code }}</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                🔒 El código se genera automáticamente y no puede modificarse
                            </div>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Alerta</label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                required
                            >
                                <option value="">Seleccionar tipo</option>
                                <option value="desvio_ruta">Desvío de Ruta</option>
                                <option value="parada_prolongada">Parada Prolongada</option>
                                <option value="velocidad_excesiva">Velocidad Excesiva</option>
                                <option value="combustible_bajo">Combustible Bajo</option>
                                <option value="mantenimiento_vencido">Mantenimiento Vencido</option>
                                <option value="zona_restringida">Zona Restringida</option>
                            </select>
                            <div v-if="form.errors.type" class="mt-2 text-sm text-red-600">{{ form.errors.type }}</div>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prioridad</label>
                            <select
                                id="priority"
                                v-model="form.priority"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                required
                            >
                                <option value="">Seleccionar prioridad</option>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="critica">Crítica</option>
                            </select>
                            <div v-if="form.errors.priority" class="mt-2 text-sm text-red-600">{{ form.errors.priority }}</div>
                        </div>

                        <div class="lg:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors resize-none"
                                placeholder="Descripción de la regla y cuándo debe activarse..."
                            ></textarea>
                            <div v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Condiciones de Activación -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Condiciones de Activación</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Define cuándo debe activarse esta regla</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="threshold_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valor Umbral</label>
                            <input
                                id="threshold_value"
                                v-model="form.threshold_value"
                                type="number"
                                step="0.01"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 250 (metros), 60 (km/h), 30 (minutos)"
                            />
                            <div v-if="form.errors.threshold_value" class="mt-2 text-sm text-red-600">{{ form.errors.threshold_value }}</div>
                        </div>

                        <div>
                            <label for="threshold_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unidad</label>
                            <select
                                id="threshold_unit"
                                v-model="form.threshold_unit"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar unidad</option>
                                <option value="metros">Metros</option>
                                <option value="kilometros">Kilómetros</option>
                                <option value="minutos">Minutos</option>
                                <option value="horas">Horas</option>
                                <option value="kmh">Km/h</option>
                                <option value="litros">Litros</option>
                                <option value="porcentaje">Porcentaje</option>
                            </select>
                            <div v-if="form.errors.threshold_unit" class="mt-2 text-sm text-red-600">{{ form.errors.threshold_unit }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios y Días -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Horarios de Activación</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Define cuándo está activa esta regla</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="active_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hora de Inicio</label>
                            <input
                                id="active_from"
                                v-model="form.active_from"
                                type="time"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            />
                            <div v-if="form.errors.active_from" class="mt-2 text-sm text-red-600">{{ form.errors.active_from }}</div>
                        </div>

                        <div>
                            <label for="active_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hora de Fin</label>
                            <input
                                id="active_to"
                                v-model="form.active_to"
                                type="time"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            />
                            <div v-if="form.errors.active_to" class="mt-2 text-sm text-red-600">{{ form.errors.active_to }}</div>
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Días de la Semana</label>
                            <div class="grid grid-cols-7 gap-2">
                                <label v-for="(day, index) in daysOfWeek" :key="index" class="flex items-center">
                                    <input
                                        v-model="form.active_days"
                                        :value="index"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                                    />
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ day }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Estado de la Regla</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Controla si la regla está activa</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="flex items-center">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50"
                        />
                        <label for="is_active" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Regla activa
                        </label>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Solo las reglas activas pueden generar alertas automáticamente.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-end space-x-4">
                        <Link
                            :href="route('alert-rules.show', alertRule.id)"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-500 rounded-lg font-semibold text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 border border-transparent rounded-lg font-bold text-sm text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transition-all duration-200"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>Guardar Cambios</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import {
    ArrowLeftIcon
} from '@heroicons/vue/24/outline'

interface Props {
    alertRule: {
        id: number
        name: string
        code: string
        type: string
        priority: string
        description?: string
        threshold_value?: number
        threshold_unit?: string
        active_from?: string
        active_to?: string
        active_days?: string
        is_active: boolean
    }
}

const props = defineProps<Props>()

const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Alertas y Monitoreo',
        href: '/alerts'
    },
    {
        title: 'Configuración de Reglas',
        href: '/alerts?panel=configuracion'
    },
    {
        title: props.alertRule.name,
        href: `/alert-rules/${props.alertRule.id}`
    },
    {
        title: 'Editar',
        href: `/alert-rules/${props.alertRule.id}/edit`
    }
]

// Parse active days from JSON
let activeDays: number[] = []
if (props.alertRule.active_days) {
    try {
        activeDays = JSON.parse(props.alertRule.active_days)
    } catch {
        activeDays = []
    }
}

const form = useForm({
    name: props.alertRule.name,
    type: props.alertRule.type,
    priority: props.alertRule.priority,
    description: props.alertRule.description || '',
    threshold_value: props.alertRule.threshold_value,
    threshold_unit: props.alertRule.threshold_unit || '',
    active_from: props.alertRule.active_from || '',
    active_to: props.alertRule.active_to || '',
    active_days: activeDays,
    is_active: props.alertRule.is_active,
})

const submit = () => {
    form.put(route('alert-rules.update', props.alertRule.id), {
        onSuccess: () => {
            // Redirect to show page after successful update
        }
    })
}
</script>
