import {NavigationGuardNext, RouteLocationNormalized} from 'vue-router';
import {useAuthStore} from "../../store/auth.store";

export function authGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
): void {
    const authStore = useAuthStore();

    if (!authStore.isLoggedIn) {
        next({ name: 'sign-in' });
    } else {
        next();
    }
}

export function createPermissionGuard(requiredPermissionLevel: number = 1) {
    return async (
        to: RouteLocationNormalized,
        from: RouteLocationNormalized,
        next: NavigationGuardNext
    ): Promise<void> => {
        const authStore = useAuthStore();

        if (!authStore.isLoggedIn) {
            next({ name: 'sign-in' });
            return;
        }

        try {
            const user = await authStore.user();
            const userPermissionLevel = user.permissionLevel;

            if (userPermissionLevel >= requiredPermissionLevel) {
                next();
            } else {
                next({ name: '403' });
            }
        } catch (error) {
            console.error('Error checking permissions:', error);
            next({ name: '403' });
        }
    };
}
