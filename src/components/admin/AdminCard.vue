<script setup lang="ts">
const props = defineProps<{
    class?: string
}>();
</script>

<template>
    <div :class="['px-10 py-7 bg-white border-[#D6DDE1] border-1 rounded-3xl shadow-md', props.class]">
        <slot />
    </div>
</template>
