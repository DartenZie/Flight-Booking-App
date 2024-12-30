<script setup lang="ts">
import {Flight, FlightResult} from "@/models/flight.model";

const API_URL = process.env.VITE_API_URL;

defineProps<{
    flight: Flight
}>();

const smallestPrice = (flight: Flight): number => {
    return Math.min(...flight.prices.values());
};
</script>

<template>
    <div v-if="flight" class="flex gap-x-4 mb-12">
        <div class="w-1/3 flex gap-x-4 items-center">
            <div class="shrink-0">
                <img
                    class="size-12 object-contain"
                    :src="`${API_URL}/airline/logo?airlineId=${flight.plane.airlineId}`"
                    :alt="`${flight.plane.airline} Logo`"
                />
            </div>
            <div class="text-xl font-medium text-black">
                {{ flight.plane.airline }}
            </div>
        </div>

        <div class="w-1/3 flex gap-x-8 items-center ms-10">
            <div class="w-12 flex flex-col items-end">
                <div class="text-lg font-medium text-slate-950">
                    {{ FlightResult.formatTime(flight.departureTime) }}
                </div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.departureAirport.iata }}
                </div>
            </div>
            <div class="border-b-2 border-dashed border-slate-950 w-28"></div>
            <div class="w-16">
                <div class="text-lg font-medium text-slate-950">
                    {{ FlightResult.formatTime(flight.arrivalTime) }}
                </div>
                <div class="text-sm font-light text-slate-600">
                    {{ flight.arrivalAirport.iata }}
                </div>
            </div>
            <div class="text-lg font-normal text-slate-800">
                {{ FlightResult.formatDuration((flight.arrivalTime - flight.departureTime) / 60000) }}
            </div>
        </div>

        <div class="w-1/3 flex items-center justify-end">
            <div class="me-24">
                <!--                        <div class="text-right">-->
                <!--                            <font-awesome-icon :icon="faShoePrints" class="text-xs me-3" />-->
                <!--                            <span class="font-thin">Leg room: </span>-->
                <!--                            <span class="font-medium">74 cm</span>-->
                <!--                        </div>-->
                <div class="text-right font-thin text-xs">
                    {{ flight.plane.airline }} &#183;  {{ flight.plane.name }}
                </div>
            </div>
            <div class="flex gap-x-1.5 items-center">
                <div class="font-medium text-xl text-slate-950">
                    {{ smallestPrice(flight) }}
                </div>
                <div class="font-light text-sm text-slate-700">EUR</div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
