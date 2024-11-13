<script setup lang="ts">
import floatingUIState from '@/store/floatingUIStore';
import { computed, onMounted } from 'vue';

const props = defineProps<{
    componentId: string,
    dropdownItems: ReadonlyArray<{ name: string; value: string }>,
    position?: 'left' | 'center' | 'right'
}>();

const emit = defineEmits<{
    (e: 'select', id: string): void,
    (e: 'label', label: string): void,
}>();

const isOpen = computed(() => floatingUIState.isOpen(props.componentId));

const selectElement = (id: string, label: string): void => {
    emit('select', id);
    emit('label', label);
};
</script>

<template>
    <!--
          Dropdown menu, show/hide based on menu state.

          Entering: "transition ease-out duration-100"
            From: "transform opacity-0 scale-95"
            To: "transform opacity-100 scale-100"
          Leaving: "transition ease-in duration-75"
            From: "transform opacity-100 scale-100"
            To: "transform opacity-0 scale-95"
        -->

    <div v-if="isOpen" :class="['absolute translate-y-4 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none', position === 'right' ? 'right-0' : position === 'center' ? 'right-1/2 translate-x-1/2' : 'left-0']" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <div class="py-1" role="none">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <a v-for="item in dropdownItems"
               :key="item.name"
               @click="selectElement(item.name, item.value)"
               class="block text-left px-4 py-2 text-sm text-gray-700 cursor-pointer hover:text-gray-900 hover:bg-gray-100"
               role="menuitem" tabindex="-1">
                {{ item.value }}
            </a>
        </div>
    </div>
</template>
