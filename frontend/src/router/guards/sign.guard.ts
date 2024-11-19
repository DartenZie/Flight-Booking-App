import {NavigationGuardNext, RouteLocationNormalized} from 'vue-router';
import {useAuthStore} from "../../store/auth.store";

export function signGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext,
): void {
    const authStore = useAuthStore();

    if (authStore.isLoggedIn) {
        next({ name: 'profile' });
    } else {
        next();
    }
}
