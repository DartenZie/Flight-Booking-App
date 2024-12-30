import {defineStore} from "pinia";
import {ref, watch} from "vue";
import {Flight, FlightResponse} from "../models/flight.model";
import {useFetch} from "@vueuse/core";

const API_URL = process.env.VITE_API_URL;

export const useReservationStore = defineStore('reservation', () => {
    const departureFlightId = ref<number>(11);
    const departureFlight = ref<Flight>(null);
    const returnFlightId = ref<number>(0);
    const returnFlight = ref<Flight>(null);

    async function fetchFlight(flightId: number): Promise<Flight> {
        if (flightId <= 0) {
            return null;
        }

        try {
            const { data } = await useFetch<FlightResponse>(`${API_URL}/flight?id=${flightId}`).get().json();
            return Flight.parseFlight(data.value);
        } catch (error) {
            console.error('Failed to fetch flight:', error);
        }
    }

    watch(
        () => departureFlightId.value,
        async () => { departureFlight.value = await fetchFlight(departureFlightId.value); },
        { immediate: true }
    );
    watch(
        () => returnFlightId.value,
        async () => { returnFlight.value = await fetchFlight(returnFlightId.value); },
        { immediate: true }
    );

    return { departureFlightId, departureFlight, returnFlightId, returnFlight };
});
