<script setup lang="ts">
import { computed } from 'vue';

import {Flight, FlightResultType} from "@/models/flight.model";

const props = defineProps<{
    flight: FlightResultType,
    belowAveragePrice?: boolean
}>();

defineEmits<{
    (e: 'select'): void,
}>();

const departureTime = computed(() => props.flight.getDepartureTime() ?? '');
const arrivalTime = computed(() => props.flight.getArrivalTime() ?? '');
const duration = computed(() => props.flight.getFormattedDuration()) ?? '';

const lowestPrice = computed(() => {
    const priceMap = Flight.parseFlightPrices(props.flight.price);
    return Math.min(...priceMap.values());
});

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};
</script>

<template>
    <div
        class="px-4 pt-6 pb-4 md:p-6 w-full bg-white rounded-xl border border-gray-100 shadow-lg md:flex justify-between md:items-center md:gap-x-4"
    >
        <div class="xl:w-1/4 mb-4 md:mb-0 flex gap-x-4 items-center">
            <div class="shrink-0">
                <img
                    class="size-4 md:size-6 lg:size-10 xl:size-12 object-contain"
                    :src="flight.airline.logo"
                    :alt="`${flight.airline.name} Logo`"
                    @error="handleImageError"
                />
            </div>
            <div class="text-md md:text-lg xl:text-xl font-medium text-black">
                {{ flight.airline.name }}
            </div>
        </div>
        <div class="xl:w-2/4 flex gap-x-8 items-center xl:ms-10">
            <div class="w-12 flex flex-col items-end">
                <div class="text-lg font-medium text-slate-950">{{ departureTime }}</div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.departure.name }}
                </div>
            </div>
            <div class="relative border-b-2 border-dashed border-slate-950 w-28">
                <div class="absolute top-1/2 -translate-y-1/2 text-center w-full -mt-3.5 text-sm md:text-md font-normal text-slate-800 lg:hidden ">{{ duration }}</div>
            </div>
            <div class="w-16">
                <div class="text-lg font-medium text-slate-950">{{ arrivalTime }}</div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.arrival.name }}
                </div>
            </div>

            <div class="hidden lg:block text-lg font-normal text-slate-800">{{ duration }}</div>

            <div class="md:hidden flex gap-x-1.5 items-center">
                <div :class="['font-medium text-xl', belowAveragePrice ? 'text-green-600' : 'text-slate-950']">
                    {{ lowestPrice }}
                </div>
                <div :class="['font-light text-sm', belowAveragePrice ? 'text-green-800' : 'text-slate-700']">EUR</div>
            </div>
        </div>
        <div class="xl:w-1/4 mt-4 pt-3 md:mt-0 md:pt-0 flex justify-end items-center gap-x-8 border-t border-dashed md:border-none">
            <div class="hidden md:flex gap-x-1.5 items-center">
                <div :class="['font-medium text-xl', belowAveragePrice ? 'text-green-600' : 'text-slate-950']">
                    {{ lowestPrice }}
                </div>
                <div :class="['font-light text-sm', belowAveragePrice ? 'text-green-800' : 'text-slate-700']">EUR</div>
            </div>
            <div>
                <button class="btn-primary h-8 md:h-12 text-sm md:text-md" @click="$emit('select')">{{ flight.type === 'outbound' ? 'Select Return' : flight.type === 'selected' ? 'Change Outbound' : 'Book Now' }}</button>
            </div>
        </div>
    </div>
</template>
