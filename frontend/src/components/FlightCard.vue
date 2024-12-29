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
        class="p-6 w-full bg-white rounded-xl shadow-lg flex items-center gap-x-4"
    >
        <div class="w-1/4 flex gap-x-4 items-center">
            <div class="shrink-0">
                <img
                    class="size-12 object-contain"
                    :src="flight.airline.logo"
                    :alt="`${flight.airline.name} Logo`"
                    @error="handleImageError"
                />
            </div>
            <div class="text-xl font-medium text-black">
                {{ flight.airline.name }}
            </div>
        </div>
        <div class="w-2/4 flex gap-x-8 items-center ms-10">
            <div class="w-12 flex flex-col items-end">
                <div class="text-lg font-medium text-slate-950">{{ departureTime }}</div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.departure.name }}
                </div>
            </div>
            <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
            <div class="w-16">
                <div class="text-lg font-medium text-slate-950">{{ arrivalTime }}</div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.arrival.name }}
                </div>
            </div>
            <div class="text-lg font-normal text-slate-800">{{ duration }}</div>
        </div>
        <div class="w-1/4 flex justify-end items-center gap-x-8">
            <div class="flex gap-x-1.5 items-center">
                <div :class="['font-medium text-xl', belowAveragePrice ? 'text-green-600' : 'text-slate-950']">
                    {{ lowestPrice }}
                </div>
                <div :class="['font-light text-sm', belowAveragePrice ? 'text-green-800' : 'text-slate-700']">EUR</div>
            </div>
            <div>
                <button class="btn-primary h-12" @click="$emit('select')">{{ flight.type === 'outbound' ? 'Select Return' : flight.type === 'selected' ? 'Change Outbound' : 'Book Now' }}</button>
            </div>
        </div>
    </div>
</template>
