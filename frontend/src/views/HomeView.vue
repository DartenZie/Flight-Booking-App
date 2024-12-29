<script setup lang="ts">
import {onMounted, ref} from 'vue';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {faChevronDown, faPlane} from '@fortawesome/free-solid-svg-icons';
import { faArrowRight } from '@fortawesome/free-solid-svg-icons';

import FloatingUiDropdown from '@/components/floating-ui/FloatingUiDropdown.vue';
import SearchInput from '@/components/SearchInput.vue';
import SearchDatePick from '@/components/SearchDatePick.vue';
import FlightCard from '@/components/FlightCard.vue';

import router from '@/router';
import {FlightResult, type FlightResultType} from "@/models/flight.model";
import {useFetch} from "@vueuse/core";
import {useReservationStore} from "@/store/reservation.store";

const API_URL = process.env.VITE_API_URL;

const reservationStore = useReservationStore();

const flightTypeId = ref('roundTrip');
const flightTypeLabel = ref('');

const flightTypeOptions: ReadonlyArray<{ name: string; value: string }> = [
    { name: 'roundTrip', value: 'Round Trip' },
    { name: 'oneWayTrip', value: 'One Way Trip' },
];

const fromLocation = ref({});
const toLocation = ref({});

const flights = ref<ReadonlyArray<FlightResultType>>([
]);

const returnFlights = ref<ReadonlyArray<FlightResult>>([
]);

const outboundFlight = ref<FlightResultType | null>(null);

const handleSelect = (flight: FlightResultType): void => {
    switch (flight.type) {
    case 'outbound':
        flight.type = 'selected';
        outboundFlight.value = flight;
        break;
    case 'selected':
        flight.type = 'outbound';
        outboundFlight.value = null;
        break;
    case 'oneWay':
        reservationStore.departureFlightId = flight.id;
        router.push('/book/passenger-information');
        break;
    case 'return':
        router.push('/book/passenger-information');
        break;
    }
};

const handleSearch = async () => {
    if (!fromLocation.value?.id || !toLocation.value?.id) {
        return;
    }

    const body = {
        departureAirportId: fromLocation.value?.id,
        arrivalAirportId: toLocation.value?.id,
        departureDate: '2024-12-30',
        type: 'oneway'
    };
    const { data } = await useFetch(`${API_URL}/flight/search`).post(body).json();

    flights.value = data.value.flights.map((flight) => new FlightResult(
        flight.id,
        { name: flight.plane.airlineName, logo: `${API_URL}/airline/logo?airlineId=${flight.plane.airlineId}` },
        { name: flight.departureAirport.iata, time: new Date(flight.departureTime) },
        { name: flight.arrivalAirport.iata, time: new Date(flight.arrivalTime) },
        flight.price,
        'oneWay'
    ));
};

onMounted(async () => {
    flightTypeLabel.value = flightTypeOptions[0].value;
});
</script>

<template>
    <div class="bg-hero bg-cover bg-center h-160">
        <div
            class="container mx-auto h-full flex flex-auto items-center justify-start"
        >
            <p class="font-thin text-5xl leading-snug text-gray-900">
                Hey Buddy? where are you<br />
                <span class="font-medium">Flying</span> to?
            </p>
        </div>
    </div>
    <div class="container mx-auto -mt-32">
        <div
            class="relative h-68 bg-white rounded-lg shadow-md px-10 pt-6 pb-12"
        >
            <h2 class="font-medium text-lg mb-4">
                <font-awesome-icon :icon="faPlane" />
                <span id="flightTest" class="ms-3">Flight</span>
            </h2>

            <div class="flex gap-10 mb-6">
                <div class="relative inline-block text-left">
                    <div class="floating-ui-activator">
                        <a v-floating-ui-trigger="{ componentId: 'flightSearch:flightType' }"
                           class="text-sm font-medium cursor-pointer select-none">
                            <span class="me-3">{{ flightTypeLabel }}</span>
                            <font-awesome-icon :icon="faChevronDown" />
                        </a>
                    </div>

                    <floating-ui-dropdown
                        component-id="flightSearch:flightType"
                        :dropdown-items="flightTypeOptions"
                        @label="l => (flightTypeLabel = l)"
                        @select="id => (flightTypeId = id)"
                    />
                </div>
            </div>

            <div class="h-20 w-full flex gap-x-6">
                <div class="w-[30%]">
                    <search-input
                        id="fromLocation"
                        label="From"
                        v-model="fromLocation"
                        placeholder="Frankfurt"
                    />
                </div>
                <div class="w-[30%]">
                    <search-input
                        id="toLocation"
                        label="To"
                        v-model="toLocation"
                        placeholder="Denmark"
                    />
                </div>
                <div class="w-[40%]">
                    <search-date-pick id="searchDate" :type="flightTypeId" />
                </div>
            </div>

            <button
                class="btn-primary absolute bottom-0 right-10 h-16 w-56 translate-y-1/2 text-left"
                @click="handleSearch"
            >
                <span>Search Flights</span>
                <font-awesome-icon
                    :icon="faArrowRight"
                    class="absolute right-6 bottom-1/2 translate-y-1/2"
                />
            </button>
        </div>
    </div>

    <div class="container mt-24 mb-24 mx-auto">
        <template v-if="outboundFlight">
            <div class="mb-6">
                <h2 class="text-2xl font-medium leading-normal">Selected flight</h2>
            </div>

            <div class="mb-12">
                <flight-card
                    :flight="outboundFlight"
                    @select="handleSelect(outboundFlight)"
                />
            </div>
        </template>

        <template v-if="!outboundFlight">
            <div class="flex gap-x-6 mb-6 items-center">
                <h2 class="text-2xl font-medium leading-normal">Flights</h2>
                <div class="h-5 w-[1px] bg-slate-600"></div>
                <p class="text-sm font-normal leading-normal">
                    Total <span class="font-medium">126 results</span>
                </p>
            </div>

            <div class="flex flex-col gap-y-6">
                <flight-card
                    v-for="(flight, index) in flights"
                    :key="index"
                    :flight="flight"
                    @select="handleSelect(flight)"
                />
            </div>
        </template>

        <template v-if="outboundFlight">
            <div class="flex gap-x-6 mb-6 items-center">
                <h2 class="text-2xl font-medium leading-normal">Return Flights</h2>
                <div class="h-5 w-[1px] bg-slate-600"></div>
                <p class="text-sm font-normal leading-normal">
                    Total <span class="font-medium">126 results</span>
                </p>
            </div>

            <div class="flex flex-col gap-y-6">
                <flight-card
                    v-for="(flight, index) in returnFlights"
                    :key="index"
                    :flight="flight"
                    :below-average-price="flight.price < 100"
                    @select="handleSelect(flight)"
                />
            </div>
        </template>
    </div>
</template>
