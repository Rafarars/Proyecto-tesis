<template>
    <AppLayout title="Gestión de Usuarios" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                        <Users class="h-8 w-8 text-white" />
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Administra los conductores y personal del sistema de geolocalización</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <Link
                        :href="route('drivers.create')"
                        class="inline-flex items-center bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <UserPlus class="w-5 h-5 mr-2" />
                        Nuevo Conductor
                    </Link>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Conductores -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-blue-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Conductores</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl shadow-md">
                        <Users class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>

            <!-- Activos -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-green-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-xl shadow-md">
                        <CheckCircle class="h-8 w-8 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            <!-- Licencias Vencidas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-red-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Licencias Vencidas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.expired_licenses }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/30 dark:to-red-800/30 rounded-xl shadow-md">
                        <AlertTriangle class="h-8 w-8 text-red-600 dark:text-red-400" />
                    </div>
                </div>
            </div>

            <!-- Exámenes Médicos Vencidos -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 border-l-4 border-orange-500 hover:shadow-2xl transition-all duration-300 ring-1 ring-gray-900/5 dark:ring-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Exámenes Médicos Vencidos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.expired_medical }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-xl shadow-md">
                        <Heart class="h-8 w-8 text-orange-600 dark:text-orange-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl mb-6 border-l-4 border-gray-400 dark:border-gray-600 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                        <input
                            v-model="searchForm.search"
                            type="text"
                            placeholder="Placa, código o marca..."
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            @input="debouncedSearch"
                        >
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado</label>
                        <select
                            v-model="searchForm.status"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            @change="search"
                        >
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="vacaciones">Vacaciones</option>
                            <option value="licencia">Licencia</option>
                            <option value="suspendido">Suspendido</option>
                        </select>
                    </div>

                    <!-- Filtro por turno -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Turno</label>
                        <select
                            v-model="searchForm.shift"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            @change="search"
                        >
                            <option value="">Todos los turnos</option>
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                            <option value="noche">Noche</option>
                            <option value="rotativo">Rotativo</option>
                        </select>
                    </div>

                    <!-- Filtro por estado de licencia -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estado Licencia</label>
                        <select
                            v-model="searchForm.license_status"
                            class="w-full h-11 px-4 rounded-lg border-2 border-gray-300 dark:border-gray-500 bg-white dark:bg-slate-700 text-gray-900 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-800 transition-colors"
                            @change="search"
                        >
                            <option value="">Todos</option>
                            <option value="vigente">Vigente</option>
                            <option value="por_vencer">Por Vencer</option>
                            <option value="vencida">Vencida</option>
                        </select>
                    </div>
                </div>

                <!-- Botón limpiar filtros -->
                <div class="mt-4 flex justify-end">
                    <button
                        @click="clearFilters"
                        class="w-full h-11 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white font-bold px-4 rounded-lg transition duration-200"
                    >
                        <X class="h-4 w-4 mr-1 inline" />
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de conductores -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-xl ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('employee_code')">
                                <div class="flex items-center space-x-1">
                                    <span>Código</span>
                                    <ArrowUpDown class="h-4 w-4" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('first_name')">
                                <div class="flex items-center space-x-1">
                                    <span>Nombre Completo</span>
                                    <ArrowUpDown class="h-4 w-4" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Documento
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Licencia
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('status')">
                                <div class="flex items-center space-x-1">
                                    <span>Estado</span>
                                    <ArrowUpDown class="h-4 w-4" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('shift')">
                                <div class="flex items-center space-x-1">
                                    <span>Turno</span>
                                    <ArrowUpDown class="h-4 w-4" />
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehículo Asignado
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="driver in drivers.data" :key="driver.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ driver.employee_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <User class="h-5 w-5 text-emerald-600" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ driver.first_name }} {{ driver.last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ driver.email || 'Sin email' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ driver.document_type.toUpperCase() }}</div>
                                    <div class="text-gray-500">{{ driver.document_number }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <div class="font-medium">{{ driver.license_type }} - {{ driver.license_number }}</div>
                                    <div class="text-gray-500">Vence: {{ formatDate(driver.license_expiry) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusBadgeClass(driver.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getStatusLabel(driver.status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span :class="getShiftBadgeClass(driver.shift)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                                    {{ getShiftLabel(driver.shift) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div v-if="driver.current_vehicle">
                                    <div class="font-medium">{{ driver.current_vehicle.license_plate }}</div>
                                    <div class="text-gray-500">{{ driver.current_vehicle.brand }} {{ driver.current_vehicle.model }}</div>
                                </div>
                                <span v-else class="text-gray-400 italic">Sin asignar</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <Link
                                        :href="route('drivers.show', driver.id)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 dark:text-blue-400 dark:bg-blue-950/50 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-colors duration-200"
                                        title="Ver detalles"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </Link>
                                    <Link
                                        :href="route('drivers.edit', driver.id)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:text-green-700 dark:text-green-400 dark:bg-green-950/50 dark:border-green-800 dark:hover:bg-green-900/50 transition-colors duration-200"
                                        title="Editar"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </Link>
                                    <button
                                        @click="confirmDelete(driver)"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-700 dark:text-red-400 dark:bg-red-950/50 dark:border-red-800 dark:hover:bg-red-900/50 transition-colors duration-200"
                                        title="Eliminar"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="drivers.data.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <Pagination :links="drivers.links" />
            </div>

            <!-- Estado vacío -->
            <div v-else class="text-center py-12">
                <Users class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay conductores</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Comienza agregando un nuevo conductor al sistema.
                </p>
                <!-- Debug info -->
                <div class="mt-4 p-4 bg-gray-100 rounded text-left text-xs">
                    <p><strong>Debug Info:</strong></p>
                    <p>Drivers object: {{ drivers }}</p>
                    <p>Drivers.data: {{ drivers?.data }}</p>
                    <p>Drivers.data.length: {{ drivers?.data?.length }}</p>
                    <p>Stats: {{ stats }}</p>
                </div>
                <div class="mt-6">
                    <Link
                        :href="route('drivers.create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <UserPlus class="h-4 w-4 mr-2" />
                        Nuevo Conductor
                    </Link>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <ConfirmationModal :show="showDeleteModal" @close="showDeleteModal = false">
            <template #title>
                Eliminar Conductor
            </template>

            <template #content>
                ¿Estás seguro de que deseas eliminar al conductor <strong>{{ driverToDelete?.first_name }} {{ driverToDelete?.last_name }}</strong>?
                Esta acción no se puede deshacer.
            </template>

            <template #footer>
                <SecondaryButton @click="showDeleteModal = false">
                    Cancelar
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': deleteForm.processing }"
                    :disabled="deleteForm.processing"
                    @click="deleteDriver"
                >
                    Eliminar
                </DangerButton>
            </template>
        </ConfirmationModal>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, Link, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import AppLayout from '@/layouts/AppLayout.vue';
import Pagination from '@/components/Pagination.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import SecondaryButton from '@/components/SecondaryButton.vue';
import DangerButton from '@/components/DangerButton.vue';
import {
    Users,
    UserPlus,
    User,
    CheckCircle,
    AlertTriangle,
    Heart,
    Search,
    X,
    ArrowUpDown,
    Eye,
    Edit,
    Trash2,
} from 'lucide-vue-next';

// Props
const props = defineProps({
    drivers: Object,
    stats: Object,
    filters: Object,
    sort: Object,
});

// Debug logs
console.log('Drivers data:', props.drivers);
console.log('Drivers data length:', props.drivers?.data?.length);
console.log('Stats:', props.stats);

// Breadcrumbs
const breadcrumbs = [
    {
        title: 'Gestión de Usuarios',
        href: '/drivers'
    }
];

// Estado reactivo
const showDeleteModal = ref(false);
const driverToDelete = ref(null);

// Formularios
const searchForm = reactive({
    search: props.filters.search || '',
    status: props.filters.status || '',
    shift: props.filters.shift || '',
    license_status: props.filters.license_status || '',
});

const deleteForm = useForm({});

// Métodos de búsqueda
const search = () => {
    router.get(route('drivers.index'), {
        ...searchForm,
        sort: props.sort.field,
        direction: props.sort.direction,
    }, {
        preserveState: true,
        replace: true,
    });
};

const debouncedSearch = debounce(search, 300);

const clearFilters = () => {
    Object.keys(searchForm).forEach(key => {
        searchForm[key] = '';
    });
    search();
};

const sortBy = (field) => {
    const direction = props.sort.field === field && props.sort.direction === 'asc' ? 'desc' : 'asc';

    router.get(route('drivers.index'), {
        ...searchForm,
        sort: field,
        direction: direction,
    }, {
        preserveState: true,
        replace: true,
    });
};

// Métodos de eliminación
const confirmDelete = (driver) => {
    driverToDelete.value = driver;
    showDeleteModal.value = true;
};

const deleteDriver = () => {
    deleteForm.delete(route('drivers.destroy', driverToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            driverToDelete.value = null;
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
</script>
