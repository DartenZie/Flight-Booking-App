import {defineStore} from "pinia";
import {ref, watch} from "vue";
import {useRouter} from "vue-router";
import {useAuthenticatedFetch} from "../utils/authenticated-fetch";

const API_URL = process.env.VITE_API_URL;

export interface Airline {
    id: number;
    name: string;
    logoUrl: string;
}

export const useAirlineStore = defineStore('airline', () => {
    const router = useRouter();

    const currentRoute = router.currentRoute;
    const airline = ref<Airline>(null);

    async function fetchAirline(): Promise<void> {
        const airlineId = Number(currentRoute.value.params?.airlineId) || 0;

        if (airlineId <= 0) {
            airline.value = null;
            return;
        }

        try {
            const { data } = await useAuthenticatedFetch(`${API_URL}/airline?id=${airlineId}`).get().json();
            airline.value = {
                id: airlineId,
                name: data.value.name,
                logoUrl: `${API_URL}/airline/logo?airlineId=${airlineId}&cacheBuster=${Date.now()}`
            };
        } catch (error) {
            console.error('Failed to fetch airline:', error);
        }
    }

    watch(
        () => currentRoute.value.params?.airlineId,
        () => {
            // noinspection JSIgnoredPromiseFromCall
            fetchAirline();
        },
        { immediate: true },
    );

    function invalidateCache(): void {
        // noinspection JSIgnoredPromiseFromCall
        fetchAirline();
    }

    return { airline, invalidateCache };
});
