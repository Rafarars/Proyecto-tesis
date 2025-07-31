<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Iniciar Sesión" description="Inicie sesión para acceder al sistema de geolocalización">
        <Head title="Iniciar Sesión - Sistema de Geolocalización Carúpano" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="text-slate-700 dark:text-slate-300">Correo Electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="correo@ejemplo.com"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-slate-700 dark:text-slate-300">Contraseña</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" :tabindex="5">
                            ¿Olvidó su contraseña?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="Contraseña"
                        class="border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 dark:border-slate-600 dark:focus:border-emerald-400"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3 text-slate-700 dark:text-slate-300">
                        <Checkbox id="remember" v-model="form.remember" :tabindex="3" class="border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-600" />
                        <span>Recordarme</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-700 hover:to-blue-700 text-white font-medium py-2.5 transition-all duration-200 shadow-lg hover:shadow-xl" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                    {{ form.processing ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
                </Button>
            </div>

            <div class="text-center text-sm text-slate-600 dark:text-slate-400">
                ¿No tiene una cuenta?
                <TextLink :href="route('register')" :tabindex="5" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Registrarse</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
