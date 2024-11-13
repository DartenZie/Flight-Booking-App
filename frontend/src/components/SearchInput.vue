<script setup lang="ts">
import {computed} from "vue";

defineProps<{
    id: string,
    label: string
}>();

const model = defineModel<{ name: string, location: string }>();

const name = computed(() => model.value?.name);
const location = computed(() => model.value?.location);
</script>

<template>
    <div class="border border-slate-200 rounded-lg px-6 h-24">
        <div class="h-full relative">
            <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">{{ label }}</label>
            <input :id="id" type="text" v-model="name"
                   class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl p-0 bottom-1/2 translate-y-1/2 w-full" />
            <p class="absolute font-normal text-xs border-b-2 border-transparent bottom-2">{{ location }}</p>
        </div>
    </div>
</template>
