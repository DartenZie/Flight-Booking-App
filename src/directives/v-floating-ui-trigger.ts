import type {DirectiveBinding} from "vue";
import floatingUIState from "@/store/floatingUIStore";

// Floating UI trigger element class
const FLOATING_UI_TRIGGER_CLASS = 'floating-ui-trigger';

interface FloatingUITriggerDirectiveOptions {
    componentId: string;
}

export default {
    mounted: (el: HTMLElement, binding: DirectiveBinding<FloatingUITriggerDirectiveOptions>): void => {
        const { componentId } = binding.value;

        const toggleOpen = (): void => {
            if (floatingUIState.isOpen(componentId)) {
                floatingUIState.close();
            } else {
                floatingUIState.open(componentId);
            }
        };

        const handleClickOutside = (event: MouseEvent): void => {
            if (!(event.target as HTMLElement)?.closest(`.${FLOATING_UI_TRIGGER_CLASS}`)) {
                floatingUIState.close();
            }
        };

        el.classList.add(FLOATING_UI_TRIGGER_CLASS);
        el.addEventListener('click', toggleOpen);
        document.addEventListener('click', handleClickOutside);

        // Clean up event listeners when element is unmounted
        el._floatingUICleanup = () => {
            el.removeEventListener('click', toggleOpen);
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
