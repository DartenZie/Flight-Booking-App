import {defineStore} from "pinia";
import {ref} from "vue";
import {useRouter} from "vue-router";
import {useAuthenticatedFetch} from "../utils/authenticated-fetch";

const API_URL = 'http://localhost:8080';

export interface Airline {
    id: number;
    name: string;
}

export const useAirlineStore = defineStore('airline', () => {
    const router = useRouter();
    const airlineId = router.currentRoute.value.params.airlineId;

    const cachedAirline = ref<Airline>(null);
    const cachedAirlineId = ref<number>(0);

    async function airline(): Promise<Airline> {
        if (cachedAirline.value && cachedAirline.value.id !== airlineId) {
            return cachedAirline.value;
        }

        try {
            const { data } = await useAuthenticatedFetch(`${API_URL}/airline?id=${airlineId}`).get().json();

            cachedAirline.value = {
                id: data.value.id,
                name: data.value.name
            };
            cachedAirlineId.value = data.value.id;

            return cachedAirline.value;
        } catch (error) {
            console.error('Failed to fetch airline: ', error);
            return null;
        }
    }

    return { airline };
});
