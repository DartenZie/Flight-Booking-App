import {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {useReservationStore} from "../../store/reservation.store";
import {useAuthStore} from "../../store/auth.store";

export function passengerInformationGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
): void {
    const reservationStore = useReservationStore();
    const authStore = useAuthStore();

    if (!reservationStore.departureFlightId) {
        next({ name: 'home' });
    } else if (!authStore.isLoggedIn) {
        next({ name: 'sign-in', query: { callback: to.fullPath }, });
    } if (!authStore.user.firstName || !authStore.user.lastName || !authStore.user.email || !authStore.user.sex
        || !authStore.user.dateOfBirth || !authStore.user.nationality || !authStore.user.phone) {
        next();
    } else {
        next({ name: 'seat-reservation' });
    }
}
