<script setup lang="ts">
import {createConfirmDialog} from "vuejs-confirm-dialog";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faLocationDot,
    faChevronDown,
    faPen,
    faPlaneDeparture,
    faPlaneArrival, faRotateRight, faClock
} from "@fortawesome/free-solid-svg-icons";

import AdminCard from "@/components/admin/AdminCard.vue";
import FloatingUiDropdown from "@/components/floating-ui/FloatingUiDropdown.vue";
import ConfirmFlightCancelModal from "@/components/modals/ConfirmFlightCancelModal.vue";
import ManageFlightPricesModal from "@/components/modals/ManageFlightPricesModal.vue";
import {onMounted, ref} from "vue";
import {Flight, FlightResponse, FlightsResponse, UpdateFlightRequest} from "@/models/flight.model";
import {useFetch} from "@vueuse/core";
import {useRouter} from "vue-router";

const API_URL = process.env.VITE_API_URL;

const router = useRouter();

const airlineId = router.currentRoute.value.params.airlineId;

const manageFlightPricesDialog = createConfirmDialog(ManageFlightPricesModal, {});
const confirmFlightCancel = createConfirmDialog(ConfirmFlightCancelModal, {});

const flights = ref<ReadonlyArray<Flight>>(null);

const duration = (flight: Flight) => {
    const durationInSeconds = (flight.arrivalTime.valueOf() - flight.departureTime.valueOf()) / 1000;

    if (durationInSeconds <= 0) {
        return "0min";
    }

    const minutes = Math.floor(durationInSeconds / 60);
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;

    let result = "";

    if (hours > 0) {
        result += `${hours}h `;
    }

    if (remainingMinutes > 0) {
        result += `${remainingMinutes}min`;
    }

    return result.trim();
};

const formatTime = (time: Date) => {
    const hours = String(time.getHours()).padStart(2, '0');
    const minutes = String(time.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
};

const month = (flight: Flight) => {
    return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][flight.departureTime.getMonth()];
};

onMounted(async () => {
    const { data } = await useFetch<FlightsResponse>(`${API_URL}/flight/airline?id=${airlineId}`).get().json();
    flights.value = data.value.flights.map((flight) => Flight.parseFlight(flight));
});

manageFlightPricesDialog.onConfirm(async ({ flight }) => {
    const priceStrings = [];
    for (const priceKey of flight.prices.keys()) {
        priceStrings.push(`[${priceKey} ${flight.prices.get(priceKey)}]`);
    }

    const body: UpdateFlightRequest = {
        id: flight.id,
        price: priceStrings.join(' ')
    };
    const response = await useFetch<FlightResponse>(`${API_URL}/flight`).put(body).json();
    if (response.statusCode.value !== 200) {
        confirmFlightCancel.close();
        return;
    }

    const { data } = await useFetch<FlightsResponse>(`${API_URL}/flight/airline?id=${airlineId}`).get().json();
    flights.value = data.value.flights.map((flight) => Flight.parseFlight(flight));

    manageFlightPricesDialog.close();
});
manageFlightPricesDialog.onCancel(manageFlightPricesDialog.close);

confirmFlightCancel.onConfirm(async ({ id }) => {
    const body: UpdateFlightRequest = {
        id: id,
        cancelled: true
    };
    const response = await useFetch<FlightResponse>(`${API_URL}/flight`).put(body).json();
    if (response.statusCode.value !== 200) {
        confirmFlightCancel.close();
        return;
    }

    const { data } = await useFetch<FlightsResponse>(`${API_URL}/flight/airline?id=${airlineId}`).get().json();
    flights.value = data.value.flights.map((flight) => Flight.parseFlight(flight));

    confirmFlightCancel.close();
});
confirmFlightCancel.onCancel(confirmFlightCancel.close);

const handleSelect = (key: string, flightId: number) => {
    switch (key) {
    case 'manageTickets':
        manageFlightPricesDialog.reveal({ flight: flights.value.find((flight) => flight.id === flightId) });
        break;
    case 'cancelFlight':
        confirmFlightCancel.reveal({ id: flightId });
        break;
    }
};

const reinstateFlight = async (flightId: number): Promise<void> => {
    const body: UpdateFlightRequest = {
        id: flightId,
        cancelled: false
    };
    const response = await useFetch<FlightResponse>(`${API_URL}/flight`).put(body).json();
    if (response.statusCode.value !== 200) {
        confirmFlightCancel.close();
        return;
    }

    const { data } = await useFetch<FlightsResponse>(`${API_URL}/flight/airline/?id=${airlineId}`).get().json();
    flights.value = data.value.flights.map((flight) => Flight.parseFlight(flight));
};
</script>

<template>
    <admin-card class="hidden lg:flex col-span-12 h-28 justify-end">
        <router-link :to="`/airline/${airlineId}/manage-flights/schedule`" class="btn-primary">Schedule flight</router-link>
    </admin-card>

    <admin-card class="col-span-12">
        <h2 class="text-2xl font-semibold mb-2">Scheduled flights</h2>
        <p class="text-md text-gray-700 mb-6">See your scheduled flights in the calendar view.</p>

        <div class="lg:hidden mb-6 flex justify-end">
            <router-link :to="`/airline/${airlineId}/manage-flights/schedule`" class="btn-primary h-14">Schedule flight</router-link>
        </div>

        <div class="grid grid-cols-1 gap-y-2 pb-24">
            <div v-for="flight of flights" :key="flight.id"
                 :class="[
                     'border border-gray-200 rounded-2xl md:h-20 grid grid-cols-12 md:flex md:items-center md:gap-x-10',
                     flight.cancelled ? 'bg-red-100' : ''
                 ]">
                <div v-if="!flight.cancelled" class="col-span-3 text-center md:w-20 md:min-w-20 text-gray-700 border-b border-e md:border-b-0 border-gray-200">
                    <div class="text-lg font-normal">{{ month(flight) }}</div>
                    <div class="text-2xl font-bold">{{ flight.departureTime.getDate() }}</div>
                </div>

                <div v-else class="col-span-3 flex items-center justify-end h-full md:block md:h-auto text-end md:w-20 md:min-w-20 text-red-700">
                    <div class="text-sm font-normal">Cancelled</div>
                </div>

                <div class="col-span-6 flex flex-col justify-center border-b border-e md:border-b-0 md:border-e-0 border-gray-200 md:block">
                    <div class="px-3 md:px-0 flex gap-x-2 items-center mb-1">
                        <font-awesome-icon :icon="faClock" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">
                            {{ formatTime(flight.departureTime) }} - {{ formatTime(flight.arrivalTime) }}
                        </span>
                    </div>
                    <div class="px-3 md:px-0 flex gap-x-2 items-center">
                        <font-awesome-icon :icon="faLocationDot" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">{{ flight.departureAirport.iata }} - {{ flight.arrivalAirport.iata }}</span>
                    </div>
                </div>

                <div class="col-span-3 border-b md:border-b-0 border-gray-200 md:hidden flex justify-center items-center">
                    <button v-if="!flight.cancelled" class="block relative mx-auto btn-text h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${flight.id}` }">
                        <font-awesome-icon :icon="faPen" />

                        <floating-ui-dropdown :component-id="`edit-flight-${flight.id}`" position="right" @select="(key) => handleSelect(key, flight.id)" :dropdown-items="[
                            { name: 'manageTickets', value: 'Manage ticket prices' },
                            { name: 'cancelFlight', value: 'Cancel flight' },
                        ]" />
                    </button>

                    <button v-else class="block relative mx-auto btn-text h-12" @click="reinstateFlight(flight.id)">
                        <font-awesome-icon :icon="faRotateRight" />
                    </button>
                </div>

                <div class="col-span-12">
                    <div class="hidden 2xl:block mb-1">
                        <div class="text-sm mb-1">{{ duration(flight) }} flight from <span class="font-semibold">{{ flight.departureAirport.name }}</span> to <span class="font-semibold">{{ flight.arrivalAirport.name }}</span></div>
                    </div>
                    <div class="hidden md:block 2xl:hidden mb-1">
                        <div class="text-sm mb-1">{{ duration(flight) }} flight from <span class="font-semibold">{{ flight.departureAirport.iata }}</span> to <span class="font-semibold">{{ flight.arrivalAirport.iata }}</span></div>
                    </div>

                    <div class="md:hidden text-sm mb-2 px-6 mt-2">
                        <div class="flex items-center gap-x-6 mb-1">
                            <font-awesome-icon :icon="faPlaneDeparture" class="text-gray-700" />
                            <div class="font-medium">{{ flight.departureAirport.name }}</div>
                        </div>
                        <div class="flex items-center gap-x-6">
                            <font-awesome-icon :icon="faPlaneArrival" class="text-gray-700" />
                            <div class="font-medium">{{ flight.arrivalAirport.name }}</div>
                        </div>
                    </div>

                    <div class="text-xs text-gray-700 font-light px-6 mb-4 md:mb-0 md:px-0">{{ flight.plane.name }} &#183; {{ flight.plane.calculateTotalSeats() }} Seats</div>
                </div>

                <button v-if="!flight.cancelled" class="hidden md:block relative ms-auto me-8 btn-primary h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${flight.id}` }">
                    <span class="me-7">Edit</span>
                    <font-awesome-icon :icon="faChevronDown" />

                    <floating-ui-dropdown :component-id="`edit-flight-${flight.id}`" position="right" @select="(key) => handleSelect(key, flight.id)" :dropdown-items="[
                        { name: 'manageTickets', value: 'Manage ticket prices' },
                        { name: 'cancelFlight', value: 'Cancel flight' },
                    ]" />
                </button>
                <button v-else class="hidden md:block relative ms-auto me-8 btn-primary h-12" @click="reinstateFlight(flight.id)">
                    <span>Reinstate</span>
                </button>
            </div>

            <div v-if="!flights || flights.length <= 0" class="mt-20 w-full relative flex justify-center">
                <div class="max-w-xl">
                    <img class="mb-10" src="../../assets/illustrations/empty_illustration.svg" alt="Empty illustration" />
                    <h4 class="text-xl text-center">No Flights Found</h4>
                    <p class="text-sm text-gray-700 text-center">There are currently no flights available matching the criteria.</p>
                </div>
            </div>
        </div>
    </admin-card>
</template>
