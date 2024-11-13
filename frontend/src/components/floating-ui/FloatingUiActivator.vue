<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted } from 'vue';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';

import floatingUIState from '@/store/floatingUIStore';

const props = defineProps<{
    label?: string;
    componentId: string;
}>();

const isOpen = computed(() => floatingUIState.isOpen(props.componentId));
const displayLabel = computed(() => props.label ?? 'Open');

const toggleOpen = () => {
    if (isOpen.value) {
        floatingUIState.close();
    } else {
        floatingUIState.open(props.componentId);
    }
};

const handleClickOutside = (event: MouseEvent): void => {
    if (!(event.target as HTMLElement)?.closest('.floating-ui-activator')) {
        floatingUIState.close();
    }
};

// Initialize component.
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

// Destroy component.
onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative inline-block text-left">
        <div class="floating-ui-activator">
            <a class="text-sm font-medium cursor-pointer select-none" @click="toggleOpen">
                <span class="me-3">{{ displayLabel }}</span>
                <font-awesome-icon :icon="faChevronDown" />
            </a>
        </div>

        <slot />
    </div>
</template>
