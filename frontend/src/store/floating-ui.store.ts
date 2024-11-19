import {defineStore} from "pinia";
import {ref} from "vue";

export const useFloatingUiStore = defineStore('floating-ui', () => {
    const openComponentId = ref('');

    function open(id: string): void {
        openComponentId.value = id;
    }

    function close(): void {
        openComponentId.value = '';
    }

    function isOpen(id: string): boolean {
        return openComponentId.value === id;
    }

    return { open, close, isOpen };
});
