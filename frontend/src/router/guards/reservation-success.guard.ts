import {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {useReservationStore} from "../../store/reservation.store";

export function reservationSuccessGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
) {
    const reservationStore = useReservationStore();
    if (!reservationStore.reservationSuccess) {
        next({ name: 'seat-reservation' });
    } else {
        next();
    }
}
