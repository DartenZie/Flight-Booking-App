import { reactive } from 'vue';

const floatingUIState = reactive({
    openComponentId: '',

    open(id: string): void {
        this.openComponentId = id;
    },

    close(): void {
        this.openComponentId = '';
    },

    isOpen(id: string): boolean {
        return this.openComponentId === id;
    }
});

export default floatingUIState;
