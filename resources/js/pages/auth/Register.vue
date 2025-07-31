<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Crear Cuenta" description="Ingrese sus datos para crear una cuenta en el sistema">
        <Head title="Registro - Sistema de Geolocalización Carúpano" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name" class="text-slate-700 dark:text-slate-300">Nombre Completo</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        v-model="form.name"
                        placeholder="Nombre y apellido"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-slate-700 dark:text-slate-300">Correo Electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="correo@ejemplo.com"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-slate-700 dark:text-slate-300">Contraseña</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Contraseña"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-slate-700 dark:text-slate-300">Confirmar Contraseña</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirmar contraseña"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-4 w-full bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-medium py-2.5 transition-all duration-200 shadow-lg hover:shadow-xl" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                    {{ form.processing ? 'Creando cuenta...' : 'Crear Cuenta' }}
                </Button>
            </div>

            <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                ¿Ya tiene una cuenta?
                <TextLink :href="route('login')" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium" :tabindex="6">Iniciar Sesión</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
