<script setup lang="ts">
const props = defineProps({
    class: { type: String },
    padding: { type: Boolean, default: true }
});
</script>

<template>
    <div :class="['bg-white border-[#D6DDE1] border-1 rounded-3xl shadow-md overflow-hidden', props.padding ? 'px-6 lg:px-10 py-5 lg:py-7' : '', props.class]">
        <slot />
    </div>
</template>
