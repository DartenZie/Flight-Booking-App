<script setup lang="ts">
defineProps<{
    label: string,
    type?: 'text' | 'password' | 'date',
}>();
const model = defineModel();
</script>

<template>
    <div class="relative">
        <input
            v-model="model"
            :type="type ?? 'text'"
            class="peer w-full bg-white placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded-md px-3 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow" />
        <label class="absolute cursor-text bg-white px-1 left-2.5 top-2.5 text-slate-400 text-sm transition-all transform origin-left peer-focus:-top-2 peer-focus:left-2.5 peer-focus:text-xs peer-focus:text-slate-400 peer-focus:scale-90">
            {{ label }}
        </label>
    </div>
</template>
