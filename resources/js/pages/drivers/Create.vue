<template>
    <AppLayout title="Nuevo Conductor" :breadcrumbs="breadcrumbs">
        <!-- Panel de título -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl p-6 mb-6 border border-emerald-200 dark:border-emerald-800 ring-1 ring-gray-900/5 dark:ring-white/10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-blue-500 rounded-lg">
                        <UserPlus class="h-8 w-8 text-white" />
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nuevo Conductor</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Registra un nuevo conductor en el sistema de geolocalización</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
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

        <div class="max-w-4xl mx-auto">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Información Personal -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <User class="h-5 w-5 mr-2 text-emerald-600" />
                            Información Personal
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Código de empleado -->
                            <div>
                                <label for="employee_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Código de Empleado *
                                </label>
                                <input
                                    id="employee_code"
                                    v-model="form.employee_code"
                                    type="text"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.employee_code }"
                                />
                                <p v-if="form.errors.employee_code" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.employee_code }}
                                </p>
                            </div>

                            <!-- Nombres -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombres *
                                </label>
                                <input
                                    id="first_name"
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.first_name }"
                                />
                                <p v-if="form.errors.first_name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.first_name }}
                                </p>
                            </div>

                            <!-- Apellidos -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Apellidos *
                                </label>
                                <input
                                    id="last_name"
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.last_name }"
                                />
                                <p v-if="form.errors.last_name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.last_name }}
                                </p>
                            </div>

                            <!-- Tipo de documento -->
                            <div>
                                <label for="document_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tipo de Documento *
                                </label>
                                <select
                                    id="document_type"
                                    v-model="form.document_type"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.document_type }"
                                >
                                    <option value="">Seleccionar tipo</option>
                                    <option value="cedula">Cédula</option>
                                    <option value="pasaporte">Pasaporte</option>
                                    <option value="extranjeria">Extranjería</option>
                                </select>
                                <p v-if="form.errors.document_type" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.document_type }}
                                </p>
                            </div>

                            <!-- Número de documento -->
                            <div>
                                <label for="document_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Número de Documento *
                                </label>
                                <input
                                    id="document_number"
                                    v-model="form.document_number"
                                    type="text"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.document_number }"
                                />
                                <p v-if="form.errors.document_number" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.document_number }}
                                </p>
                            </div>

                            <!-- Fecha de nacimiento -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha de Nacimiento *
                                </label>
                                <input
                                    id="birth_date"
                                    v-model="form.birth_date"
                                    type="date"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.birth_date }"
                                />
                                <p v-if="form.errors.birth_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.birth_date }}
                                </p>
                            </div>

                            <!-- Género -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                    Género *
                                </label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.gender }"
                                >
                                    <option value="">Seleccionar género</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <p v-if="form.errors.gender" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.gender }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <Phone class="h-5 w-5 mr-2 text-emerald-600" />
                            Información de Contacto
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Teléfono -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Teléfono Principal
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.phone }"
                                />
                                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.phone }}
                                </p>
                            </div>

                            <!-- Teléfono de emergencia -->
                            <div>
                                <label for="phone_emergency" class="block text-sm font-medium text-gray-700 mb-1">
                                    Teléfono de Emergencia
                                </label>
                                <input
                                    id="phone_emergency"
                                    v-model="form.phone_emergency"
                                    type="tel"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.phone_emergency }"
                                />
                                <p v-if="form.errors.phone_emergency" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.phone_emergency }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Correo Electrónico
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Dirección de Residencia
                            </label>
                            <textarea
                                id="address"
                                v-model="form.address"
                                rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                :class="{ 'border-red-300': form.errors.address }"
                            ></textarea>
                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                                {{ form.errors.address }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información Laboral -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <Briefcase class="h-5 w-5 mr-2 text-emerald-600" />
                            Información Laboral
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Fecha de contratación -->
                            <div>
                                <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha de Contratación *
                                </label>
                                <input
                                    id="hire_date"
                                    v-model="form.hire_date"
                                    type="date"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.hire_date }"
                                />
                                <p v-if="form.errors.hire_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.hire_date }}
                                </p>
                            </div>

                            <!-- Estado laboral -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Estado Laboral *
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.status }"
                                >
                                    <option value="">Seleccionar estado</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="vacaciones">Vacaciones</option>
                                    <option value="licencia">Licencia</option>
                                    <option value="suspendido">Suspendido</option>
                                </select>
                                <p v-if="form.errors.status" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <!-- Turno -->
                            <div>
                                <label for="shift" class="block text-sm font-medium text-gray-700 mb-1">
                                    Turno de Trabajo *
                                </label>
                                <select
                                    id="shift"
                                    v-model="form.shift"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.shift }"
                                >
                                    <option value="">Seleccionar turno</option>
                                    <option value="mañana">Mañana</option>
                                    <option value="tarde">Tarde</option>
                                    <option value="noche">Noche</option>
                                    <option value="rotativo">Rotativo</option>
                                </select>
                                <p v-if="form.errors.shift" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.shift }}
                                </p>
                            </div>

                            <!-- Salario -->
                            <div>
                                <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">
                                    Salario Base
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input
                                        id="salary"
                                        v-model="form.salary"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                        :class="{ 'border-red-300': form.errors.salary }"
                                    />
                                </div>
                                <p v-if="form.errors.salary" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.salary }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Licencias y Certificaciones -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <CreditCard class="h-5 w-5 mr-2 text-emerald-600" />
                            Licencias y Certificaciones
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número de licencia -->
                            <div>
                                <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Número de Licencia *
                                </label>
                                <input
                                    id="license_number"
                                    v-model="form.license_number"
                                    type="text"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.license_number }"
                                />
                                <p v-if="form.errors.license_number" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.license_number }}
                                </p>
                            </div>

                            <!-- Tipo de licencia -->
                            <div>
                                <label for="license_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tipo de Licencia *
                                </label>
                                <select
                                    id="license_type"
                                    v-model="form.license_type"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.license_type }"
                                >
                                    <option value="">Seleccionar tipo</option>
                                    <option value="A">Tipo A - Motocicletas</option>
                                    <option value="B">Tipo B - Vehículos livianos</option>
                                    <option value="C">Tipo C - Vehículos pesados</option>
                                    <option value="D">Tipo D - Transporte público</option>
                                    <option value="E">Tipo E - Vehículos especiales</option>
                                </select>
                                <p v-if="form.errors.license_type" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.license_type }}
                                </p>
                            </div>

                            <!-- Vencimiento de licencia -->
                            <div>
                                <label for="license_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                    Vencimiento de Licencia *
                                </label>
                                <input
                                    id="license_expiry"
                                    v-model="form.license_expiry"
                                    type="date"
                                    required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.license_expiry }"
                                />
                                <p v-if="form.errors.license_expiry" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.license_expiry }}
                                </p>
                            </div>

                            <!-- Certificación materiales peligrosos -->
                            <div class="flex items-center">
                                <input
                                    id="has_hazmat_cert"
                                    v-model="form.has_hazmat_cert"
                                    type="checkbox"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                />
                                <label for="has_hazmat_cert" class="ml-2 block text-sm text-gray-900">
                                    Certificación para Materiales Peligrosos
                                </label>
                            </div>

                            <!-- Vencimiento certificación materiales peligrosos -->
                            <div v-if="form.has_hazmat_cert">
                                <label for="hazmat_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                    Vencimiento Certificación Materiales Peligrosos
                                </label>
                                <input
                                    id="hazmat_expiry"
                                    v-model="form.hazmat_expiry"
                                    type="date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.hazmat_expiry }"
                                />
                                <p v-if="form.errors.hazmat_expiry" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.hazmat_expiry }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Médica -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <Heart class="h-5 w-5 mr-2 text-emerald-600" />
                            Información Médica y Seguridad
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Fecha último examen médico -->
                            <div>
                                <label for="medical_exam_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha Último Examen Médico
                                </label>
                                <input
                                    id="medical_exam_date"
                                    v-model="form.medical_exam_date"
                                    type="date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.medical_exam_date }"
                                />
                                <p v-if="form.errors.medical_exam_date" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.medical_exam_date }}
                                </p>
                            </div>

                            <!-- Vencimiento examen médico -->
                            <div>
                                <label for="medical_exam_expiry" class="block text-sm font-medium text-gray-700 mb-1">
                                    Vencimiento Examen Médico
                                </label>
                                <input
                                    id="medical_exam_expiry"
                                    v-model="form.medical_exam_expiry"
                                    type="date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.medical_exam_expiry }"
                                />
                                <p v-if="form.errors.medical_exam_expiry" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.medical_exam_expiry }}
                                </p>
                            </div>

                            <!-- Restricciones médicas -->
                            <div class="md:col-span-2">
                                <label for="medical_restrictions" class="block text-sm font-medium text-gray-700 mb-1">
                                    Restricciones Médicas
                                </label>
                                <textarea
                                    id="medical_restrictions"
                                    v-model="form.medical_restrictions"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.medical_restrictions }"
                                    placeholder="Especificar cualquier restricción médica relevante para la conducción..."
                                ></textarea>
                                <p v-if="form.errors.medical_restrictions" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.medical_restrictions }}
                                </p>
                            </div>

                            <!-- Contacto de emergencia -->
                            <div class="md:col-span-2">
                                <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contacto de Emergencia
                                </label>
                                <textarea
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors.emergency_contact }"
                                    placeholder="Nombre, relación, teléfono del contacto de emergencia..."
                                ></textarea>
                                <p v-if="form.errors.emergency_contact" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.emergency_contact }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notas Adicionales -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <FileText class="h-5 w-5 mr-2 text-emerald-600" />
                            Notas Adicionales
                        </h3>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Observaciones
                            </label>
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="4"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                                :class="{ 'border-red-300': form.errors.notes }"
                                placeholder="Cualquier información adicional relevante sobre el conductor..."
                            ></textarea>
                            <p v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                                {{ form.errors.notes }}
                            </p>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                                />
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Conductor activo en el sistema
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3">
                    <Link
                        :href="route('drivers.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50"
                    >
                        <span v-if="form.processing" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                        </span>
                        <span v-else class="inline-flex items-center">
                            <Save class="h-4 w-4 mr-2" />
                            Guardar Conductor
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    ArrowLeft,
    User,
    Phone,
    Briefcase,
    CreditCard,
    Heart,
    FileText,
    Save,
    UserPlus,
} from 'lucide-vue-next';

// Breadcrumbs
const breadcrumbs = computed(() => [
    { name: 'Gestión de Usuarios', href: route('drivers.index') },
    { name: 'Nuevo Conductor', href: '#' },
]);

// Formulario
const form = useForm({
    // Información personal
    employee_code: '',
    first_name: '',
    last_name: '',
    document_type: '',
    document_number: '',
    birth_date: '',
    gender: '',

    // Información de contacto
    phone: '',
    phone_emergency: '',
    email: '',
    address: '',

    // Información laboral
    hire_date: '',
    status: 'activo',
    shift: '',
    salary: '',

    // Licencias y certificaciones
    license_number: '',
    license_type: '',
    license_expiry: '',
    has_hazmat_cert: false,
    hazmat_expiry: '',

    // Información médica
    medical_exam_date: '',
    medical_exam_expiry: '',
    medical_restrictions: '',
    emergency_contact: '',

    // Auditoría
    notes: '',
    is_active: true,
});

// Métodos
const submit = () => {
    form.post(route('drivers.store'));
};
</script>
