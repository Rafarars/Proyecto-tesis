<template>
    <AppLayout title="Detalles del Conductor" :breadcrumbs="breadcrumbs">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-white">
                        Detalles del Conductor
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ driver.employee_code }} - {{ driver.first_name }} {{ driver.last_name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <Link
                        :href="route('drivers.edit', driver.id)"
                        class="h-11 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center shadow-sm"
                    >
                        <Edit class="w-5 h-5 mr-2" />
                        Editar
                    </Link>
                    <Link
                        :href="route('drivers.index')"
                        class="h-11 bg-gray-600 hover:bg-gray-700 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold px-4 rounded-lg transition duration-200 flex items-center shadow-sm"
                    >
                        <ArrowLeft class="w-5 h-5 mr-2" />
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
                        <User class="h-8 w-8 text-white" />
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles del Conductor</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa de {{ driver.first_name }} {{ driver.last_name }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <Link
                            :href="route('drivers.index')"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors duration-200"
                        >
                            <ArrowLeft class="w-4 h-4 mr-2" />
                            Volver
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Información principal -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Información Personal -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-emerald-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <User class="h-5 w-5 text-emerald-600 dark:text-emerald-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información Personal</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Código de Empleado</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.employee_code }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nombre Completo</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.first_name }} {{ driver.last_name }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Documento</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.document_type.toUpperCase() }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Número de Documento</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.document_number }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Nacimiento</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ formatDate(driver.birth_date) }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Género</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.gender.charAt(0).toUpperCase() + driver.gender.slice(1) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <Phone class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información de Contacto</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono Principal</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.phone || 'No especificado' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Teléfono de Emergencia</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.phone_emergency || 'No especificado' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Correo Electrónico</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.email || 'No especificado' }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dirección</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.address || 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Laboral -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <Briefcase class="h-5 w-5 text-purple-600 dark:text-purple-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información Laboral</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Contratación</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ formatDate(driver.hire_date) }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Estado Laboral</label>
                                    <span :class="getStatusBadgeClass(driver.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                        {{ getStatusLabel(driver.status) }}
                                    </span>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Número de Licencia</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ driver.license_number }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg p-3">
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Licencia</label>
                                    <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">Tipo {{ driver.license_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="space-y-4">
                    <!-- Vehículo Asignado -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-indigo-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <Truck class="h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Vehículo Asignado</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div v-if="currentVehicle" class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                        <Truck class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Placa</p>
                                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ currentVehicle.license_plate }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Marca:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ currentVehicle.brand }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Modelo:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ currentVehicle.model }}</span>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-6">
                                <Truck class="h-10 w-10 text-gray-400 mx-auto mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Sin vehículo asignado</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación Actual -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-cyan-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <MapPin class="h-5 w-5 text-cyan-600 dark:text-cyan-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ubicación Actual</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="space-y-3">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Latitud:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">10.6678</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Longitud:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">-63.2583</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Velocidad:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">0.00 km/h</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Actualización:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">2 de agosto de 2025</span>
                                    </div>
                                </div>
                                <button class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <MapPin class="w-4 h-4 mr-2" />
                                    Ver en Mapa
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Ruta Asignada -->
                    <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-orange-500">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <Route class="h-5 w-5 text-orange-600 dark:text-orange-400 mr-2" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Ruta Asignada</h3>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="text-center py-6">
                                <Route class="h-10 w-10 text-gray-400 mx-auto mb-2" />
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-1">Sin ruta asignada</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Las rutas se asignarán automáticamente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import Modal from '@/components/Modal.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import DangerButton from '@/components/DangerButton.vue';
import {
    ArrowLeft,
    Edit,
    User,
    Phone,
    MapPin,
    Route,
    Heart,
    Truck,
    FileText,
    Plus,
    X,
    Briefcase
} from 'lucide-vue-next';

// Props
const props = defineProps({
    driver: Object,
    currentVehicle: Object,
    availableVehicles: Array
});

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Gestión de Usuarios',
        href: '/drivers'
    },
    {
        title: 'Detalles del Conductor',
        href: `/drivers/${props.driver?.id}`
    }
];

// Reactive data
const showAssignModal = ref(false);
const showUnassignModal = ref(false);

// Forms
const assignForm = useForm({
    vehicle_id: ''
});

const unassignForm = useForm({});

// Utility functions
const formatDate = (date) => {
    if (!date) return 'No especificado';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusBadgeClass = (status) => {
    const classes = {
        'active': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        'inactive': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
        'suspended': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400';
};

const getStatusLabel = (status) => {
    const labels = {
        'active': 'Activo',
        'inactive': 'Inactivo',
        'suspended': 'Suspendido'
    };
    return labels[status] || status;
};

// Vehicle assignment functions
const assignVehicle = () => {
    assignForm.post(route('drivers.assign-vehicle', props.driver.id), {
        onSuccess: () => {
            showAssignModal.value = false;
            assignForm.reset();
        }
    });
};

const unassignVehicle = () => {
    unassignForm.delete(route('drivers.unassign-vehicle', props.driver.id), {
        onSuccess: () => {
            showUnassignModal.value = false;
        }
    });
};
</script>
