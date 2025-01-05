import {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {useAuthStore} from "../../store/auth.store";

export function seatReservationGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
): void {
    const authStore = useAuthStore();
    if (!authStore.user?.firstName || !authStore.user?.lastName || !authStore.user?.email || !authStore.user?.sex
        || !authStore.user?.dateOfBirth || !authStore.user?.nationality || !authStore.user?.phone) {
        next({ name: 'passenger-information' });
    } else {
        next();
    }
}
