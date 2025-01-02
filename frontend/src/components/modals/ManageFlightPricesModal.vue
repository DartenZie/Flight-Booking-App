<script setup lang="ts">
import {Flight} from "@/models/flight.model";

defineProps<{
    flight: Flight
}>();
</script>

<template>
    <div class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-700">Boarding ticket prices</h3>
                        <p class="text-sm text-gray-700 mb-6">Average prices for flights on this route (excluding your airline’s flights).</p>

                        <div v-for="cabin in flight.plane.seatingConfiguration.cabins" :key="cabin.id" class="flex items-center gap-x-10 mb-6">
                            <div class="w-1/3">
                                <div class="text-lg -mb-1">{{ cabin.className }}</div>
                                <!--<div class="text-sm text-gray-700">Avg. price: 84 EUR</div>-->
                            </div>
                            <div class="w-1/2">
                                <input type="number" id="economyClassPrice" placeholder="100 EUR" :value="flight.prices.get(cabin.className)" @change="flight.prices.set(cabin.className, $event.target.value)"
                                       class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 flex flex-row-reverse sm:px-6 gap-x-4">
                        <button type="button" class="btn-primary h-10" @click="$emit('confirm', { flight })">Confirm</button>
                        <button type="button" class="btn-default h-10" @click="$emit('cancel')">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

