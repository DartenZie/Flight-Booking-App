import {defineStore} from "pinia";
import {ref, watch} from "vue";
import {Flight, FlightResponse} from "../models/flight.model";
import {useFetch} from "@vueuse/core";
import {useAuthenticatedFetch} from "../utils/authenticated-fetch";
import {useRouter} from "vue-router";

const API_URL = process.env.VITE_API_URL;

export const useReservationStore = defineStore('reservation', () => {
    const router = useRouter();

    const departureFlightId = ref<number>(14);
    const departureFlight = ref<Flight>(null);
    const departureSeat = ref<string>('');
    const returnFlightId = ref<number>(0);
    const returnFlight = ref<Flight>(null);
    const returnSeat = ref<string>('');

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

    async function fetchFlightTakenSeats(flightId: number): Promise<string[]> {
        if (flightId <= 0) {
            return null;
        }

        try {
            const { data } = await useFetch(`${API_URL}/flight/seats?id=${flightId}`).get().json();
            return data.value.takenSeats;
        } catch (error) {
            console.error('Failed to fetch taken seats:', error);
        }
    }

    async function makeReservations(): Promise<void> {
        const promises: Promise<unknown>[] = [];
        if (departureFlightId.value > 0) {
            const promise = useAuthenticatedFetch(`${API_URL}/reservation`).post({
                seat: departureSeat.value,
                flightId: departureFlightId.value,
            }).json();
            promises.push(promise);
        }
        if (returnFlightId.value > 0) {
            const promise = useAuthenticatedFetch(`${API_URL}/reservation`).post({
                seat: returnSeat.value,
                flightId: returnFlightId.value,
            }).json();
            promises.push(promise);
        }

        try {
            await Promise.all(promises);
        } catch (err) {
            console.error('Failed to create reservation:', err);
            return;
        }

        await router.push('/');
    }

    watch(
        () => departureFlightId.value,
        async () => {
            if (departureFlightId.value <= 0) {
                return;
            }

            const flight = await fetchFlight(departureFlightId.value);
            flight.plane.seatingConfiguration.takenSeats = await fetchFlightTakenSeats(departureFlightId.value);
            departureFlight.value = flight;
        },
        { immediate: true }
    );
    watch(
        () => returnFlightId.value,
        async () => {
            if (returnFlightId.value <= 0) {
                return;
            }

            const flight = await fetchFlight(returnFlightId.value);
            flight.plane.seatingConfiguration.takenSeats = await fetchFlightTakenSeats(returnFlightId.value);
            returnFlight.value = flight;
        },
        { immediate: true }
    );

    return { departureFlightId, departureFlight, departureSeat, returnFlightId, returnFlight, returnSeat, makeReservations };
});
