<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted } from 'vue';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';

import {useFloatingUiStore} from '@/store/floating-ui.store';

const floatingUIStore = useFloatingUiStore();

const props = defineProps<{
    label?: string;
    componentId: string;
}>();

const isOpen = computed(() => floatingUIStore.isOpen(props.componentId));
const displayLabel = computed(() => props.label ?? 'Open');

const toggleOpen = () => {
    if (isOpen.value) {
        floatingUIStore.close();
    } else {
        floatingUIStore.open(props.componentId);
    }
};

const handleClickOutside = (event: MouseEvent): void => {
    if (!(event.target as HTMLElement)?.closest('.floating-ui-activator')) {
        floatingUIStore.close();
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
