<script setup lang="ts">
defineProps<{
    id: string,
    label: string
}>();

const model = defineModel<{ name: string, location: string }>();
</script>

<template>
    <div class="border border-slate-200 rounded-lg px-6 h-24">
        <div class="h-full relative">
            <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">{{ label }}</label>
            <input :id="id" type="text" v-model="model.name"
                   class="absolute block outline-none font-bold text-2xl bottom-1/2 translate-y-1/2 w-full" />
            <p class="absolute font-normal text-xs border-b-2 border-transparent bottom-2">{{ model.location }}</p>
        </div>
    </div>
</template>
