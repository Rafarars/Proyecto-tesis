<template>
    <Modal :show="show" @close="$emit('close')" max-width="md">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <AlertTriangle class="h-6 w-6 text-red-600" />
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <slot name="title" />
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            <slot name="content" />
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <slot name="footer" />
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/components/Modal.vue';
import { AlertTriangle } from 'lucide-vue-next';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);
</script>
