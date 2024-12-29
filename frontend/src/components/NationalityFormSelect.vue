<script setup lang="ts">
import nationalities from '@/static/nationalities.json';

const model = defineModel();
</script>

<template>
    <label for="nationality" class="block text-sm font-medium mb-2">Nationality</label>
    <select id="nationality" v-model="model" class="py-3 px-4 pe-9 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
        <option selected value="" disabled>Select nationality</option>
        <option v-for="{ nationality } in nationalities" :key="nationality" :value="nationality">{{ nationality }}</option>
    </select>
</template>
