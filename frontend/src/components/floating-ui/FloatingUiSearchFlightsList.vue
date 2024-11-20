<script setup lang="ts">
import {useFloatingUiStore} from '@/store/floating-ui.store';
import {computed} from "vue";
import {AirportModel} from "@/models/airport.model";

const floatingUIStore = useFloatingUiStore();

const props = defineProps<{
    componentId: string;
    suggestionItems: ReadonlyArray<AirportModel>;
}>();

const emit = defineEmits<{
    (e: 'select', id: number): void,
}>();

const isOpen = computed(() => floatingUIStore.isOpen(props.componentId));

const selectElement = (id: number): void => {
    emit('select', id);
};
</script>

<template>
    <div v-if="isOpen && suggestionItems.length > 0" class="absolute translate-y-1 z-10 w-full origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" tabindex="-1">
        <div class="py-1" role="none">
            <a v-for="item in suggestionItems"
               :key="item.id"
               @click="selectElement(item.id)"
               class="block text-left px-4 py-2 text-sm text-gray-700 cursor-pointer hover:text-gray-900 hover:bg-gray-100"
               role="menuitem" tabindex="-1">
                {{ item.name }}
            </a>
        </div>
    </div>
</template>
