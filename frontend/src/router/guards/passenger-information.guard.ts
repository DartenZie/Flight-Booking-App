import {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {useReservationStore} from "../../store/reservation.store";

export function passengerInformationGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext
): void {
    const reservationStore = useReservationStore();
    if (!reservationStore.departureFlightId) {
        next({ name: 'home' });
    } else {
        next();
    }
}
