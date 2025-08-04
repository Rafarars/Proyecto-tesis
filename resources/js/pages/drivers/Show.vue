<template>
    <AppLayout title="Detalles del Conductor" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                        <User class="h-8 w-8 text-white" />
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles del Conductor</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa de {{ driver.first_name }} {{ driver.last_name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Link
                        :href="route('drivers.edit', driver.id)"
                        class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <Edit class="w-5 h-5 mr-2" />
                        Editar
                    </Link>
                    <Link
                        :href="route('drivers.index')"
                        class="inline-flex items-center bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <ArrowLeft class="w-5 h-5 mr-2" />
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Información Personal -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <User class="h-5 w-5 mr-2 text-emerald-600" />
                        Información Personal
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Código de Empleado</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.employee_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.first_name }} {{ driver.last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.document_type.toUpperCase() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número de Documento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.document_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ formatDate(driver.birth_date) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Género</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.gender.charAt(0).toUpperCase() + driver.gender.slice(1) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <Phone class="h-5 w-5 mr-2 text-emerald-600" />
                        Información de Contacto
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono Principal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.phone || 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono de Emergencia</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.phone_emergency || 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Correo Electrónico</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.email || 'No especificado' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.address || 'No especificada' }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Laboral -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <Briefcase class="h-5 w-5 mr-2 text-emerald-600" />
                        Información Laboral
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Contratación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ formatDate(driver.hire_date) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado Laboral</dt>
                            <dd class="mt-1">
                                <span :class="getStatusBadgeClass(driver.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getStatusLabel(driver.status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Turno de Trabajo</dt>
                            <dd class="mt-1">
                                <span :class="getShiftBadgeClass(driver.shift)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getShiftLabel(driver.shift) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Salario Base</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.salary ? `$${Number(driver.salary).toFixed(2)}` : 'No especificado' }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Licencias y Certificaciones -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <CreditCard class="h-5 w-5 mr-2 text-emerald-600" />
                        Licencias y Certificaciones
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número de Licencia</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.license_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Licencia</dt>
                            <dd class="mt-1 text-sm text-gray-900">Tipo {{ driver.license_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vencimiento de Licencia</dt>
                            <dd class="mt-1 flex items-center space-x-2">
                                <span class="text-sm text-gray-900">{{ formatDate(driver.license_expiry) }}</span>
                                <span :class="getLicenseStatusBadgeClass(driver.license_expiry)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getLicenseStatusLabel(driver.license_expiry) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certificación Materiales Peligrosos</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ driver.has_hazmat_cert ? 'Sí' : 'No' }}
                                <span v-if="driver.has_hazmat_cert && driver.hazmat_expiry" class="text-gray-500">
                                    (Vence: {{ formatDate(driver.hazmat_expiry) }})
                                </span>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Médica -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <Heart class="h-5 w-5 mr-2 text-emerald-600" />
                        Información Médica y Seguridad
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Último Examen Médico</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.medical_exam_date ? formatDate(driver.medical_exam_date) : 'No registrado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vencimiento Examen Médico</dt>
                            <dd class="mt-1 flex items-center space-x-2">
                                <span class="text-sm text-gray-900">{{ driver.medical_exam_expiry ? formatDate(driver.medical_exam_expiry) : 'No especificado' }}</span>
                                <span v-if="driver.medical_exam_expiry" :class="getMedicalStatusBadgeClass(driver.medical_exam_expiry)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getMedicalStatusLabel(driver.medical_exam_expiry) }}
                                </span>
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Restricciones Médicas</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.medical_restrictions || 'Ninguna' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Contacto de Emergencia</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ driver.emergency_contact || 'No especificado' }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehículo Asignado -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <Truck class="h-5 w-5 mr-2 text-emerald-600" />
                        Vehículo Asignado
                    </h3>
                </div>
                <div class="p-6">
                    <div v-if="currentVehicle" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Placa</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ currentVehicle.license_plate }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Marca y Modelo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ currentVehicle.brand }} {{ currentVehicle.model }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ currentVehicle.type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                <span :class="getVehicleStatusBadgeClass(currentVehicle.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ currentVehicle.status }}
                                </span>
                            </dd>
                        </div>
                        <div class="md:col-span-2 flex space-x-3">
                            <button
                                @click="showUnassignModal = true"
                                class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                <X class="h-4 w-4 mr-1" />
                                Desasignar Vehículo
                            </button>
                        </div>
                    </div>
                    <div v-else class="text-center py-6">
                        <Truck class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin vehículo asignado</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Este conductor no tiene un vehículo asignado actualmente.
                        </p>
                        <div class="mt-6">
                            <button
                                @click="showAssignModal = true"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                            >
                                <Plus class="h-4 w-4 mr-2" />
                                Asignar Vehículo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas Adicionales -->
            <div v-if="driver.notes" class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <FileText class="h-5 w-5 mr-2 text-emerald-600" />
                        Notas Adicionales
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-900">{{ driver.notes }}</p>
                </div>
            </div>
        </div>

        <!-- Modal de Asignación de Vehículo -->
        <Modal :show="showAssignModal" @close="showAssignModal = false" max-width="md">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                        <Truck class="h-6 w-6 text-emerald-600" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Asignar Vehículo
                        </h3>
                        <div class="mt-4">
                            <label for="vehicle_select" class="block text-sm font-medium text-gray-700 mb-2">
                                Seleccionar Vehículo
                            </label>
                            <select
                                id="vehicle_select"
                                v-model="assignForm.vehicle_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                            >
                                <option value="">Seleccionar vehículo...</option>
                                <option v-for="vehicle in availableVehicles" :key="vehicle.id" :value="vehicle.id">
                                    {{ vehicle.license_plate }} - {{ vehicle.brand }} {{ vehicle.model }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    @click="assignVehicle"
                    :disabled="!assignForm.vehicle_id || assignForm.processing"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                >
                    Asignar
                </button>
                <button
                    @click="showAssignModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Cancelar
                </button>
            </div>
        </Modal>

        <!-- Modal de Confirmación de Desasignación -->
        <ConfirmationModal :show="showUnassignModal" @close="showUnassignModal = false">
            <template #title>
                Desasignar Vehículo
            </template>

            <template #content>
                ¿Estás seguro de que deseas desasignar el vehículo <strong>{{ currentVehicle?.license_plate }}</strong> del conductor?
            </template>

            <template #footer>
                <SecondaryButton @click="showUnassignModal = false">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': unassignForm.processing }"
                    :disabled="unassignForm.processing"
                    @click="unassignVehicle"
                >
                    Desasignar
                </DangerButton>
            </template>
        </ConfirmationModal>
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
    Briefcase,
    CreditCard,
    Heart,
    Truck,
    FileText,
    Plus,
    X,
} from 'lucide-vue-next';

// Props
const props = defineProps({
    driver: Object,
    currentVehicle: Object,
    availableVehicles: Array,
});

// Estado reactivo
const showAssignModal = ref(false);
const showUnassignModal = ref(false);

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

// Formularios
const assignForm = useForm({
    vehicle_id: '',
});

const unassignForm = useForm({});

// Métodos
const assignVehicle = () => {
    assignForm.post(route('drivers.assign-vehicle', props.driver.id), {
        onSuccess: () => {
            showAssignModal.value = false;
            assignForm.reset();
        },
    });
};

const unassignVehicle = () => {
    unassignForm.delete(route('drivers.unassign-vehicle', props.driver.id), {
        onSuccess: () => {
            showUnassignModal.value = false;
        },
    });
};

// Métodos de formato
const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('es-ES');
};

const getStatusBadgeClass = (status) => {
    const classes = {
        'activo': 'bg-green-100 text-green-800',
        'inactivo': 'bg-gray-100 text-gray-800',
        'vacaciones': 'bg-blue-100 text-blue-800',
        'licencia': 'bg-yellow-100 text-yellow-800',
        'suspendido': 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        'activo': 'Activo',
        'inactivo': 'Inactivo',
        'vacaciones': 'Vacaciones',
        'licencia': 'Licencia',
        'suspendido': 'Suspendido',
    };
    return labels[status] || status;
};

const getShiftBadgeClass = (shift) => {
    const classes = {
        'mañana': 'bg-yellow-100 text-yellow-800',
        'tarde': 'bg-orange-100 text-orange-800',
        'noche': 'bg-purple-100 text-purple-800',
        'rotativo': 'bg-blue-100 text-blue-800',
    };
    return classes[shift] || 'bg-gray-100 text-gray-800';
};

const getShiftLabel = (shift) => {
    const labels = {
        'mañana': 'Mañana',
        'tarde': 'Tarde',
        'noche': 'Noche',
        'rotativo': 'Rotativo',
    };
    return labels[shift] || shift;
};

const getLicenseStatusBadgeClass = (expiryDate) => {
    if (!expiryDate) return 'bg-gray-100 text-gray-800';

    const today = new Date();
    const expiry = new Date(expiryDate);
    const daysUntilExpiry = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

    if (daysUntilExpiry < 0) {
        return 'bg-red-100 text-red-800';
    } else if (daysUntilExpiry <= 30) {
        return 'bg-yellow-100 text-yellow-800';
    } else {
        return 'bg-green-100 text-green-800';
    }
};

const getLicenseStatusLabel = (expiryDate) => {
    if (!expiryDate) return 'Sin fecha';

    const today = new Date();
    const expiry = new Date(expiryDate);
    const daysUntilExpiry = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

    if (daysUntilExpiry < 0) {
        return 'Vencida';
    } else if (daysUntilExpiry <= 30) {
        return 'Por Vencer';
    } else {
        return 'Vigente';
    }
};

const getMedicalStatusBadgeClass = (expiryDate) => {
    if (!expiryDate) return 'bg-gray-100 text-gray-800';

    const today = new Date();
    const expiry = new Date(expiryDate);
    const daysUntilExpiry = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

    if (daysUntilExpiry < 0) {
        return 'bg-red-100 text-red-800';
    } else if (daysUntilExpiry <= 30) {
        return 'bg-yellow-100 text-yellow-800';
    } else {
        return 'bg-green-100 text-green-800';
    }
};

const getMedicalStatusLabel = (expiryDate) => {
    if (!expiryDate) return 'Sin fecha';

    const today = new Date();
    const expiry = new Date(expiryDate);
    const daysUntilExpiry = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

    if (daysUntilExpiry < 0) {
        return 'Vencido';
    } else if (daysUntilExpiry <= 30) {
        return 'Por Vencer';
    } else {
        return 'Vigente';
    }
};

const getVehicleStatusBadgeClass = (status) => {
    const classes = {
        'activo': 'bg-green-100 text-green-800',
        'inactivo': 'bg-gray-100 text-gray-800',
        'mantenimiento': 'bg-yellow-100 text-yellow-800',
        'fuera_servicio': 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>
