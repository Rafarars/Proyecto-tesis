<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-gradient-to-br from-slate-50 via-blue-50 to-green-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-6">
                    <!-- Logo y título del sistema -->
                    <Link :href="route('home')" class="flex flex-col items-center gap-3 font-medium">
                        <div class="mb-2 flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-600 to-blue-600 shadow-lg">
                            <!-- Espacio reservado para logo futuro -->
                            <div class="flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                                Sistema de Geolocalización
                            </h1>
                            <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">
                                Carúpano
                            </p>
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>

                    <!-- Título y descripción de la página -->
                    <div class="space-y-2 text-center">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-slate-100">{{ title }}</h2>
                        <p class="text-center text-sm text-slate-600 dark:text-slate-400">{{ description }}</p>
                    </div>
                </div>

                <!-- Contenido del formulario -->
                <div class="rounded-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 shadow-xl p-6">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
