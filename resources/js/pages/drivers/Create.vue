<template>
    <AppLayout title="Crear Conductor" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 mb-6 border border-emerald-200 dark:border-emerald-800">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nuevo Conductor</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo conductor en la flota de recolección</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl border border-gray-200 dark:border-slate-600 sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
            <form @submit.prevent="submit" class="space-y-0">
                <!-- Información Personal -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Personal</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Datos principales del conductor</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Código de empleado (autogenerado) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Código de Empleado
                            </label>
                            <input
                                type="text"
                                disabled
                                value="Se generará automáticamente"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-slate-600 text-gray-500 dark:text-gray-400 shadow-sm"
                                placeholder="Generado automáticamente"
                            >
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">El código se genera automáticamente</p>
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombres *
                            </label>
                            <input
                                v-model="form.first_name"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: Carlos"
                            >
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Apellidos *
                            </label>
                            <input
                                v-model="form.last_name"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: Rodríguez"
                            >
                        </div>

                        <!-- Tipo de documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Documento *
                            </label>
                            <select
                                v-model="form.document_type"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar tipo</option>
                                <option value="cedula">Cédula de Identidad</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="extranjeria">Extranjería</option>
                            </select>
                        </div>

                        <!-- Número de documento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Número de Documento *
                            </label>
                            <input
                                v-model="form.document_number"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: 12345678"
                            >
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Nacimiento *
                            </label>
                            <input
                                v-model="form.birth_date"
                                type="date"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                        </div>

                        <!-- Género -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Género *
                            </label>
                            <select
                                v-model="form.gender"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar género</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información de Contacto</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Datos de contacto del conductor</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Teléfono Principal
                            </label>
                            <input
                                v-model="form.phone"
                                type="tel"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: +58 414-1234567"
                            >
                        </div>

                        <!-- Teléfono de emergencia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Teléfono de Emergencia
                            </label>
                            <input
                                v-model="form.phone_emergency"
                                type="tel"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: +58 424-7654321"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Correo Electrónico
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: carlos@ejemplo.com"
                            >
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dirección de Residencia
                            </label>
                            <textarea
                                v-model="form.address"
                                rows="3"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors resize-none"
                                placeholder="Ej: Calle Bolívar, Casa #45, Centro, Carúpano"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Información Laboral -->
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Información Laboral</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Datos laborales del conductor</p>
                </div>
                <div class="p-6 bg-white dark:bg-slate-800">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Fecha de contratación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Contratación *
                            </label>
                            <input
                                v-model="form.hire_date"
                                type="date"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                        </div>

                        <!-- Estado laboral -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado Laboral *
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar estado</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="suspended">Suspendido</option>
                            </select>
                        </div>

                        <!-- Número de licencia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Número de Licencia *
                            </label>
                            <input
                                v-model="form.license_number"
                                type="text"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                                placeholder="Ej: LIC-001-2024"
                            >
                        </div>

                        <!-- Tipo de licencia -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo de Licencia *
                            </label>
                            <select
                                v-model="form.license_type"
                                required
                                class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            >
                                <option value="">Seleccionar tipo</option>
                                <option value="A">Tipo A - Motocicletas</option>
                                <option value="B">Tipo B - Vehículos livianos</option>
                                <option value="C">Tipo C - Vehículos pesados</option>
                                <option value="D">Tipo D - Transporte público</option>
                                <option value="E">Tipo E - Vehículos especiales</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="p-6 bg-gradient-to-r from-gray-50 to-white dark:from-slate-800 dark:to-slate-700 border-t border-gray-100 dark:border-slate-700">
                    <div class="flex justify-end space-x-4">
                        <Link
                            :href="route('drivers.index')"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-green-600 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                        >
                            {{ form.processing ? 'Guardando...' : 'Crear Conductor' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

// Props
const props = defineProps({
    availableVehicles: Array
});

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Gestión de Usuarios',
        href: '/drivers'
    },
    {
        title: 'Crear Conductor',
        href: '/drivers/create'
    }
];

// Form
const form = useForm({
    first_name: '',
    last_name: '',
    document_type: '',
    document_number: '',
    birth_date: '',
    gender: '',
    phone: '',
    phone_emergency: '',
    email: '',
    address: '',
    hire_date: '',
    status: 'active',
    license_number: '',
    license_type: '',
    vehicle_id: ''
});

// Submit function
const submit = () => {
    form.post(route('drivers.store'), {
        onSuccess: () => {
            // Redirect will be handled by the controller
        }
    });
};
</script>
