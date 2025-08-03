<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import { type BreadcrumbItem } from '@/types'

interface Props {
    title?: string
    breadcrumbs?: BreadcrumbItem[]
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Sistema de Geolocalización Carúpano',
    breadcrumbs: () => []
})
</script>

<template>
    <div>
        <Head :title="title" />
        
        <AppShell :breadcrumbs="breadcrumbs">
            <template #header>
                <slot name="header" />
            </template>
            
            <slot />
        </AppShell>
    </div>
</template>
