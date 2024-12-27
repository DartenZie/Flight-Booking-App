<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onMounted, ref} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {Plane, PlanesResponse} from "@/models/plane.model";
import AirportInput from "@/components/AirportInput.vue";
import {AirportModel} from "@/models/airport.model";
import {CreateFlightRequest} from "@/models/flight.model";
import {useRouter} from "vue-router";

const router = useRouter();

const planes = ref<Plane[]>(null);

const selectedPlane = ref<Plane>(null);
const departureAirport = ref<AirportModel>(null);
const arrivalAirport = ref<AirportModel>(null);
const departureTime = ref<string>('');
const arrivalTime = ref<string>('');

const prices = ref<Map<string, number>>(new Map<string, number>());

onMounted(async () => {
    const { data } = await useAuthenticatedFetch<PlanesResponse>('http://localhost:8080/plane?airline_id=1').get().json();
    planes.value = data.value.planes.map((plane) => Plane.parsePlane(plane));
});

const selectPlane = (e) => {
    selectedPlane.value = planes.value.find((p) => p.id === parseInt(e.target.value));
    prices.value.clear();
};

const submit = async () => {
    const priceStrings = [];
    for (const priceKey of prices.value.keys()) {
        priceStrings.push(`[${priceKey} ${prices.value.get(priceKey)}]`);
    }

    const body: CreateFlightRequest = {
        price: priceStrings.join(' '),
        planeId: selectedPlane.value?.id,
        departureTime: departureTime.value,
        arrivalTime: arrivalTime.value,
        departureAirportId: departureAirport.value?.id,
        arrivalAirportId: arrivalAirport.value?.id
    };

    const response = await useAuthenticatedFetch('http://localhost:8080/flight').post(body);
    if (response.statusCode.value !== 200) {
        console.error(response.statusMessage);
    } else {
        router.back();
    }
};
</script>

<template>
    <admin-card class="col-span-12">
        <form class="space-y-8" @submit.prevent="submit()">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Schedule new flight</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Fill in the information about the flight.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700 mb-6">Airplane type</h3>

                    <div class="mb-6">
                        <div class="w-1/2">
                            <label for="airplane" class="block text-sm font-medium mb-2">Airplane</label>
                            <select id="airplane" @change="selectPlane($event)"
                                    class="py-3 px-4 pe-9 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                                <option selected value="" disabled>Select Airplane</option>
                                <option v-for="plane in planes" :key="plane.id" :value="plane.id">{{ plane.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-x-10">
                        <div class="w-1/2 relative">
                            <label for="departureAirport" class="block text-sm font-medium mb-2">Departure Airport</label>
                            <airport-input id="departureAirport" label="Departure Airport" placeholder="Vaclav Havel Airport Prague" v-model="departureAirport"
                                           class="py-3 px-4 pe-9 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" />
                        </div>
                        <div class="w-1/2 relative">
                            <label for="arrivalAirport" class="block text-sm font-medium mb-2">Arrival Airport</label>
                            <airport-input id="arrivalAirport" label="Departure Airport" placeholder="Copenhagen Airport, Kastrup" v-model="arrivalAirport"
                                           class="py-3 px-4 pe-9 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700 mb-6">Departure/Arrival time</h3>

                    <div class="flex items-center gap-x-10 mb-6">
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Departure Time</label>
                            <input type="datetime-local" id="departureTime" v-model="departureTime"
                                   class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                        <div class="w-1/2">
                            <label for="lastName" class="block text-sm font-medium mb-2">Arrival Time</label>
                            <input type="datetime-local" id="arrivalTime" v-model="arrivalTime"
                                   class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="johndoe@gmail.com">
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="selectedPlane" class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <h3 class="text-md font-medium text-gray-700">Boarding ticket prices</h3>
                    <p class="text-sm text-gray-700 mb-6">Average prices for flights on this route (excluding your airline’s flights).</p>

                    <div v-for="cabin in selectedPlane.seatingConfiguration.cabins" :key="cabin.id" class="flex items-center gap-x-10 mb-6">
                        <div class="w-1/3">
                            <div class="text-lg -mb-1">{{ cabin.className }}</div>
                            <!--<div class="text-sm text-gray-700">Avg. price: 84 EUR</div>-->
                        </div>
                        <div class="w-1/2">
                            <input type="number" id="economyClassPrice" placeholder="100 EUR" @change="prices.set(cabin.className, $event.target.value)"
                                   class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-x-8">
                <router-link to="/admin/manage-flights" class="btn-text">Cancel</router-link>
                <button class="btn-primary h-10" type="submit">
                    <span class="me-7">Schedule new flight</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </form>
    </admin-card>
</template>
