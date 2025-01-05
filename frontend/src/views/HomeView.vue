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
import HeroSection from "@/components/HeroSection.vue";

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

const date = ref<[Date, Date]>();

const flights = ref<ReadonlyArray<FlightResultType>>([]);
const returnFlights = ref<ReadonlyArray<FlightResult>>([]);

const total = ref(0);
const returnTotal = ref(0);

const searched = ref(false);

const outboundFlight = ref<FlightResultType | null>(null);

const handleSelect = (flight: FlightResultType): void => {
    switch (flight.type) {
    case 'outbound':
        flight.type = 'selected';
        reservationStore.clearReservationStore();
        reservationStore.departureFlightId = flight.id;
        outboundFlight.value = flight;
        break;
    case 'selected':
        flight.type = 'outbound';
        outboundFlight.value = null;
        break;
    case 'oneWay':
        reservationStore.clearReservationStore();
        reservationStore.departureFlightId = flight.id;
        router.push('/book/passenger-information');
        break;
    case 'return':
        reservationStore.returnFlightId = flight.id;
        router.push('/book/passenger-information');
        break;
    }
};

const handleSearch = async () => {
    if (!fromLocation.value?.id || !toLocation.value?.id) {
        return;
    }

    searched.value = true;

    if (flightTypeId.value === 'roundTrip') {
        const body = {
            departureAirportId: fromLocation.value.id,
            arrivalAirportId: toLocation.value.id,
            departureDate: formatDate(date.value[0]),
            returnDate: formatDate(date.value[1]),
            type: 'round'
        };
        const { data } = await useFetch(`${API_URL}/flight/search`).post(body).json();

        flights.value = data.value.departingFlights.map((flight) => new FlightResult(
            flight.id,
            { name: flight.plane.airlineName, logo: `${API_URL}/airline/logo?airlineId=${flight.plane.airlineId}` },
            { name: flight.departureAirport.iata, time: new Date(flight.departureTime) },
            { name: flight.arrivalAirport.iata, time: new Date(flight.arrivalTime) },
            flight.price,
            'outbound'
        ));
        returnFlights.value = data.value.returningFlights.map((flight) => new FlightResult(
            flight.id,
            { name: flight.plane.airlineName, logo: `${API_URL}/airline/logo?airlineId=${flight.plane.airlineId}` },
            { name: flight.departureAirport.iata, time: new Date(flight.departureTime) },
            { name: flight.arrivalAirport.iata, time: new Date(flight.arrivalTime) },
            flight.price,
            'return'
        ));

        total.value = flights.value.length;
        returnTotal.value = returnFlights.value.length;
    } else if (flightTypeId.value === 'oneWayTrip') {
        const body = {
            departureAirportId: fromLocation.value.id,
            arrivalAirportId: toLocation.value.id,
            departureDate: formatDate(date.value[0]),
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

        total.value = flights.value.length;
    }
};

const formatDate = (date: Date): string => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");

    return `${year}-${month}-${day}`;
};

onMounted(async () => {
    flightTypeLabel.value = flightTypeOptions[0].value;
});
</script>

<template>
    <div class="lg:-mb-32">
        <hero-section>
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

            <div class="mb-6 lg:mb-0 w-full grid grid-cols-10 gap-6">
                <div class="col-span-10 md:col-span-5 xl:col-span-3">
                    <search-input
                        id="fromLocation"
                        label="From"
                        v-model="fromLocation"
                        placeholder="Frankfurt"
                    />
                </div>
                <div class="col-span-10 md:col-span-5 xl:col-span-3">
                    <search-input
                        id="toLocation"
                        label="To"
                        v-model="toLocation"
                        placeholder="Denmark"
                    />
                </div>
                <div class="col-span-10 xl:col-span-4">
                    <search-date-pick id="searchDate" v-model="date" :type="flightTypeId" />
                </div>
            </div>

            <button
                class="btn-primary h-12 ms-auto lg:ms-0 lg:absolute lg:bottom-0 lg:right-10 lg:h-16 lg:w-56 lg:translate-y-1/2 text-left"
                @click="handleSearch"
            >
                <span class="me-7 lg:me-0">Search Flights</span>
                <font-awesome-icon
                    :icon="faArrowRight"
                    class="lg:absolute lg:right-6 lg:bottom-1/2 lg:translate-y-1/2"
                />
            </button>
        </hero-section>
    </div>

    <div v-if="total > 0" class="container px-4 md:px-8 lg:px-20 xl:px-0 my-12 xl:my-24 mx-auto">
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
                    Total <span class="font-medium">{{ total }} results</span>
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
                    Total <span class="font-medium">{{ returnTotal }} results</span>
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
    <div v-else-if="searched" class="container px-4 md:px-8 lg:px-20 xl:px-0 my-12 xl:my-24 mx-auto">
        <div class="flex flex-col items-center">
            <div class="max-w-3xl">
                <img src="../assets/illustrations/empty_illustration.svg" alt="Empty illustration" class="w-full" />
            </div>
            <div class="max-w-xl">
                <p class="text-lg text-center mt-6">
                    <span>No flights have been found. Please try adjusting your search criteria.</span>
                </p>
            </div>
        </div>
    </div>
    <div v-else class="container px-4 md:px-8 lg:px-20 xl:px-0 my-12 xl:my-24 mx-auto">
        <div class="flex flex-col items-center">
            <div class="max-w-3xl">
                <img src="../assets/illustrations/quiet_street_illustration.svg" alt="No search illustration" class="mx-auto w-full" />
            </div>
            <div class="max-w-xl">
                <p class="text-lg text-center mt-6">
                    You haven't searched for any flights yet. Please use the form above to search for available flights.
                </p>
            </div>
        </div>
    </div>
</template>
