<script setup lang="ts">
import {onMounted, ref} from 'vue';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlane } from '@fortawesome/free-solid-svg-icons';
import { faArrowRight } from '@fortawesome/free-solid-svg-icons';

import FloatingUiActivator from '@/components/floating-ui/FloatingUiActivator.vue';
import FloatingUiDropdown from '@/components/floating-ui/FloatingUiDropdown.vue';
import SearchInput from '@/components/SearchInput.vue';
import SearchDatePick from '@/components/SearchDatePick.vue';
import FlightCard from '@/components/FlightCard.vue';

import router from '@/router';
import {Flight, type FlightType} from "@/models/flight.model";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";

const flightTypeLabel = ref('');
const classLabel = ref('');

const flightTypeOptions: ReadonlyArray<{ name: string; value: string }> = [
    { name: 'oneWayTrip', value: 'One Way Trip' },
    { name: 'roundTrip', value: 'Round Trip' },
];

const classesOptions: ReadonlyArray<{ name: string; value: string }> = [
    { name: 'economyClass', value: 'Economy Class' },
    { name: 'businessClass', value: 'Business Class' },
    { name: 'firstClass', value: 'First Class' },
];

const fromLocation = ref({ name: 'Prague', location: 'PRG, Europe, CZ' });
const toLocation = ref({ name: 'Copenhagen', location: 'CPH, Europe, DK' });

const flights = ref<ReadonlyArray<FlightType>>([
    new Flight(
        { name: 'WizzAir', logo: 'wizzair.png' },
        { name: 'PRG', time: new Date() },
        { name: 'CPH' },
        80,
        99,
        'outbound',
    ),
    new Flight(
        { name: 'RyanAir', logo: 'ryanair.png' },
        { name: 'PRG', time: new Date(Date.now() + 3600000 * 0.8) },
        { name: 'CPH' },
        80,
        79,
        'outbound',
    ),
    new Flight(
        { name: 'Norwegian', logo: 'norwegian.png' },
        { name: 'PRG', time: new Date(Date.now() + 3600000 * 1.3) },
        { name: 'CPH' },
        80,
        159,
        'outbound',
    ),
    new Flight(
        { name: 'Lufthansa', logo: 'lufthansa.png' },
        { name: 'PRG', time: new Date(Date.now() + 3600000 * 2.5) },
        { name: 'CPH' },
        80,
        399,
        'outbound',
    ),
]);

const returnFlights = ref<ReadonlyArray<Flight>>([
    new Flight(
        { name: 'WizzAir', logo: 'wizzair.png' },
        { name: 'CPH', time: new Date(Date.now() - 3600000 * 7.8) },
        { name: 'PRG' },
        80,
        99,
        'return',
    ),
    new Flight(
        { name: 'RyanAir', logo: 'ryanair.png' },
        { name: 'CPH', time: new Date(Date.now() - 3600000 * 5.3) },
        { name: 'PRG' },
        80,
        79,
        'return',
    ),
    new Flight(
        { name: 'Norwegian', logo: 'norwegian.png' },
        { name: 'CPH', time: new Date(Date.now() - 3600000 * 3.1) },
        { name: 'PRG' },
        80,
        159,
        'return',
    ),
    new Flight(
        { name: 'Lufthansa', logo: 'lufthansa.png' },
        { name: 'CPH', time: new Date(Date.now() - 3600000 * 1.5) },
        { name: 'PRG' },
        80,
        399,
        'return',
    ),
]);

const outboundFlight = ref<FlightType | null>(null);

const handleSelect = (flight: FlightType): void => {
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
    case 'return':
        router.push('/book/passenger-information');
        break;
    }
};

onMounted(async () => {
    flightTypeLabel.value = flightTypeOptions[0].value;
    classLabel.value = classesOptions[0].value;

    const { data } = await useAuthenticatedFetch('http://localhost:8080/airport').get().json();
    console.log(data.value);
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
                <floating-ui-activator
                    component-id="flightSearch:flightType"
                    :label="flightTypeLabel"
                >
                    <floating-ui-dropdown
                        component-id="flightSearch:flightType"
                        :dropdown-items="flightTypeOptions"
                        @label="l => (flightTypeLabel = l)"
                    />
                </floating-ui-activator>

                <floating-ui-activator
                    component-id="flightSearch:passengers"
                    label="2 Passengers"
                >
                    <!-- TODO passenger select -->
                </floating-ui-activator>

                <floating-ui-activator
                    component-id="flightSearch:class"
                    :label="classLabel"
                >
                    <floating-ui-dropdown
                        component-id="flightSearch:class"
                        :dropdown-items="classesOptions"
                        @label="l => (classLabel = l)"
                    />
                </floating-ui-activator>
            </div>

            <div class="h-20 w-full flex gap-x-6">
                <div class="w-[30%]">
                    <search-input
                        id="fromLocation"
                        label="From"
                        v-model="fromLocation"
                    />
                </div>
                <div class="w-[30%]">
                    <search-input
                        id="toLocation"
                        label="To"
                        v-model="toLocation"
                    />
                </div>
                <div class="w-[40%]">
                    <search-date-pick id="searchDate" />
                </div>
            </div>

            <button
                class="btn-primary absolute bottom-0 right-10 h-16 w-56 translate-y-1/2 text-left"
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
                    :below-average-price="flight.price < 100"
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
