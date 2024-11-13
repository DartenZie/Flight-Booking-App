<script setup lang="ts">
import {computed} from "vue";

const props = defineProps<{
    id: string,
    label: string,
    type?: 'text' | 'email' | 'password',
    placeholder?: string
}>();
const model = defineModel();

const inputType = computed(() => props.type || 'text');
</script>

<template>
    <label :for="id" class="block text-sm font-medium mb-2">{{ label }}</label>
    <input :type="inputType" v-model="model" :id="id" :placeholder="placeholder"
           class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" />
</template>
