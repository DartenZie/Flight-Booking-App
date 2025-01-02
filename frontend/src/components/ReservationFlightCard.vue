<script setup lang="ts">
import {Flight, FlightResult} from "@/models/flight.model";
import {computed} from "vue";

const API_URL = process.env.VITE_API_URL;

const props = defineProps<{
    flight: Flight
}>();

const duration = computed(() => FlightResult.formatDuration((props.flight.arrivalTime - props.flight.departureTime) / 60000));

const smallestPrice = (flight: Flight): number => {
    return Math.min(...flight.prices.values());
};
</script>

<template>
    <div class="md:flex md:gap-x-4 mb-6 md:mb-8 lg:mb-12 md:justify-between">
        <div class="xl:w-1/3 flex gap-x-4 items-center mb-2 md:mb-0">
            <div class="shrink-0">
                <img
                    class="size-4 md:size-6 lg:size-10 xl:size-12 object-contain"
                    :src="`${API_URL}/airline/logo?airlineId=${flight.plane.airlineId}`"
                    :alt="`${flight.plane.airline} Logo`"
                />
            </div>
            <div class="text-md md:text-lg xl:text-xl font-medium text-black">
                {{ flight.plane.airline }}
            </div>
        </div>

        <div class="xl:w-1/3 flex gap-x-8 items-center xl:ms-10 mb-3 md:mb-0">
            <div class="w-12 flex flex-col items-end">
                <div class="text-lg font-medium text-slate-950">
                    {{ FlightResult.formatTime(flight.departureTime) }}
                </div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.departureAirport.iata }}
                </div>
            </div>
            <div class="relative border-b-2 border-dashed border-slate-950 w-28">
                <div class="absolute top-1/2 -translate-y-1/2 text-center w-full -mt-3.5 text-sm md:text-md font-normal text-slate-800 xl:hidden ">{{ duration }}</div>
            </div>
            <div class="w-16">
                <div class="text-lg font-medium text-slate-950">
                    {{ FlightResult.formatTime(flight.arrivalTime) }}
                </div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.arrivalAirport.iata }}
                </div>
            </div>

            <div class="hidden xl:block text-lg font-normal text-slate-800">{{ duration }}</div>

            <div class="md:hidden flex gap-x-1.5 items-center">
                <div class="font-medium text-xl text-slate-950">
                    {{ smallestPrice(flight) }}
                </div>
                <div class="font-light text-sm text-slate-700">EUR</div>
            </div>
        </div>

        <div class="xl:w-1/3 md:flex items-center justify-end">
            <div class="me-8 lg:me-16 xl:me-24">
                <div class="md:text-right font-thin text-xs">
                    {{ flight.plane.airline }} &#183;  {{ flight.plane.name }}
                </div>
            </div>
            <div class="hidden md:flex gap-x-1.5 items-center">
                <div class="font-medium text-xl text-slate-950">
                    {{ smallestPrice(flight) }}
                </div>
                <div class="font-light text-sm text-slate-700">EUR</div>
            </div>
        </div>
    </div>
</template>
