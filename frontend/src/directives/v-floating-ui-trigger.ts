import type {DirectiveBinding} from "vue";
import {useFloatingUiStore} from "../store/floating-ui.store";

// Floating UI trigger element class
const FLOATING_UI_TRIGGER_CLASS = 'floating-ui-trigger';

interface FloatingUITriggerDirectiveOptions {
    componentId: string;
    triggerEvent: string;
}

export default {
    mounted: (el: HTMLElement, binding: DirectiveBinding<FloatingUITriggerDirectiveOptions>): void => {
        const floatingUIStore = useFloatingUiStore();

        const { componentId, triggerEvent } = binding.value;

        const toggleOpen = (): void => {
            if (floatingUIStore.isOpen(componentId) && triggerEvent !== 'input') {
                floatingUIStore.close();
            } else {
                floatingUIStore.open(componentId);
            }
        };

        const handleClickOutside = (event: MouseEvent): void => {
            if (!(event.target as HTMLElement)?.closest(`.${FLOATING_UI_TRIGGER_CLASS}`)) {
                floatingUIStore.close();
            }
        };

        el.classList.add(FLOATING_UI_TRIGGER_CLASS);
        el.addEventListener(triggerEvent ?? 'click', toggleOpen);
        document.addEventListener('click', handleClickOutside);

        // Clean up event listeners when element is unmounted
        el._floatingUICleanup = () => {
            el.removeEventListener(triggerEvent ?? 'click', toggleOpen);
            document.removeEventListener('click', handleClickOutside);
        };
    },
    unmounted: (el: HTMLElement): void => {
        // Invoke the cleanup function on unmount
        if (el._floatingUICleanup) {
            el._floatingUICleanup();
        }
    }
};
