import {NavigationGuardNext, RouteLocationNormalized} from "vue-router";
import {useAuthenticatedFetch} from "../../utils/authenticated-fetch";

export function airlineExistsGuard(
    to: RouteLocationNormalized,
    from: RouteLocationNormalized,
    next: NavigationGuardNext,
) {
    useAuthenticatedFetch(`http://localhost:8080/airline?id=${to.params.airlineId}`).then(({ data }) => {
        if (data.value) {
            next();
        } else {
            next({ name: '404' });
        }
    });
}
