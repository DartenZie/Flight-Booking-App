import { reactive } from 'vue';

const floatingUIState = reactive({
    openComponentId: null,

    open(id: string): void {
        this.openComponentId = id;
    },

    close(): void {
        this.openComponentId = null;
    },

    isOpen(id: string): boolean {
        return this.openComponentId === id;
    }
});

export default floatingUIState;
