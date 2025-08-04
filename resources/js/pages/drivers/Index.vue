<template>
    <AppLayout title="Gestión de Usuarios" :breadcrumbs="breadcrumbs">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Gestión de Usuarios
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Administra los conductores y personal del sistema de geolocalización
                    </p>
                </div>
                <Link
                    :href="route('drivers.create')"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <UserPlus class="h-4 w-4 mr-2" />
                    Nuevo Conductor
                </Link>
            </div>
        </template>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Users class="h-8 w-8 text-blue-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Total Conductores
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ stats.total }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <CheckCircle class="h-8 w-8 text-emerald-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Activos
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ stats.active }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <AlertTriangle class="h-8 w-8 text-red-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Licencias Vencidas
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ stats.expired_licenses }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <Heart class="h-8 w-8 text-orange-500" />
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Exámenes Médicos Vencidos
                                </dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ stats.expired_medical }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-6">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Buscar
                        </label>
                        <div class="relative">
                            <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                            <input
                                id="search"
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Nombre, código, documento, licencia..."
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                @input="debouncedSearch"
                            />
                        </div>
                    </div>

                    <!-- Filtro por estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            Estado
                        </label>
                        <select
                            id="status"
                            v-model="searchForm.status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
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
                        <label for="shift" class="block text-sm font-medium text-gray-700 mb-1">
                            Turno
                        </label>
                        <select
                            id="shift"
                            v-model="searchForm.shift"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
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
                        <label for="license_status" class="block text-sm font-medium text-gray-700 mb-1">
                            Estado Licencia
                        </label>
                        <select
                            id="license_status"
                            v-model="searchForm.license_status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
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
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <X class="h-4 w-4 mr-1" />
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de conductores -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
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
                                <div class="flex items-center space-x-2">
                                    <Link
                                        :href="route('drivers.show', driver.id)"
                                        class="text-emerald-600 hover:text-emerald-900"
                                        title="Ver detalles"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Link>
                                    <Link
                                        :href="route('drivers.edit', driver.id)"
                                        class="text-blue-600 hover:text-blue-900"
                                        title="Editar"
                                    >
                                        <Edit class="h-4 w-4" />
                                    </Link>
                                    <button
                                        @click="confirmDelete(driver)"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar"
                                    >
                                        <Trash2 class="h-4 w-4" />
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
import { ref, reactive, computed } from 'vue';
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

// Breadcrumbs
const breadcrumbs = computed(() => [
    { name: 'Gestión de Usuarios', href: '#' },
]);

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
