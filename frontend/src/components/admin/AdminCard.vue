<script setup lang="ts">
const props = defineProps({
    class: { type: String },
    padding: { type: Boolean, default: true }
});
</script>

<template>
    <div :class="['bg-white border-[#D6DDE1] border-1 rounded-3xl shadow-md', props.padding ? 'px-10 py-7' : '', props.class]">
        <slot />
    </div>
</template>
