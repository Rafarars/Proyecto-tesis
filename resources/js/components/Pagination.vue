<template>
    <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
        <div class="-mt-px flex w-0 flex-1">
            <Link
                v-if="links.prev"
                :href="links.prev"
                class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
            >
                <ChevronLeft class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                Anterior
            </Link>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <template v-for="(link, index) in links.data" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    :class="[
                        link.active
                            ? 'border-emerald-500 text-emerald-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                        'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium'
                    ]"
                    v-html="link.label"
                />
                <span
                    v-else
                    :class="[
                        link.active
                            ? 'border-emerald-500 text-emerald-600'
                            : 'border-transparent text-gray-500',
                        'inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium'
                    ]"
                    v-html="link.label"
                />
            </template>
        </div>
        <div class="-mt-px flex w-0 flex-1 justify-end">
            <Link
                v-if="links.next"
                :href="links.next"
                class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
            >
                Siguiente
                <ChevronRight class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
            </Link>
        </div>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps({
    links: {
        type: Object,
        required: true,
    },
});
</script>
