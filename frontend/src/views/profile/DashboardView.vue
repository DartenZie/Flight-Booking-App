<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import STPassportComponent from "@/components/admin/STPassportComponent.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faCircleArrowRight} from "@fortawesome/free-solid-svg-icons";
import {computed, onMounted, ref} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {Flight} from "@/models/flight.model";
import {calculateDistance} from "@/utils/general.utils";
import {useAuthStore} from "@/store/auth.store";

const API_URL = process.env.VITE_API_URL;

const authStore = useAuthStore();

const earthCircumference = 40075;
const distanceToMoon = 384400;
const distanceToMars = 225000000;

const statistics = ref<ReadonlyArray<{
    departureTime: Date;
    departureAirportId: number;
    departureLatitude: number;
    departureLongitude: number;
    arrivalTime: Date;
    arrivalAirportId: number;
    arrivalAirportLatitude: number;
    arrivalAirportLongitude: number;
    airlineId: number;
}>>(null);

const shortestFlight = ref<Flight>(null);
const longestFlight = ref<Flight>(null);

onMounted(async () => {
    const { data } = await useAuthenticatedFetch(`${API_URL}/statistics`).get().json();

    statistics.value = data.value.statistics.map((stat) => ({
        departureTime: Date.parse(stat.departureTime),
        departureAirportId: stat.departureAirportId,
        departureLatitude: stat.departureLatitude,
        departureLongitude: stat.departureLongitude,
        arrivalTime: Date.parse(stat.arrivalTime),
        arrivalAirportId: stat.arrivalAirportId,
        arrivalAirportLatitude: stat.arrivalLatitude,
        arrivalAirportLongitude: stat.arrivalLongitude,
        airlineId: stat.airlineId,
    }));

    if (data.value.shortestFlight) {
        shortestFlight.value = Flight.parseFlight(data.value.shortestFlight);
    }
    if (data.value.longestFlight) {
        longestFlight.value = Flight.parseFlight(data.value.longestFlight);
    }
});

const totalUpcomingFlights = computed(() => {
    let upcomingFlights = 0;
    const currentTime = new Date();
    statistics.value.forEach((stat) => {
        if (stat.departureTime > currentTime) {
            upcomingFlights++;
        }
    });
    return upcomingFlights;
});

const totalFlights = computed(() => statistics.value?.length);

const totalDistance = computed(() => Math.ceil(statistics.value?.reduce((acc, flight) => acc + calculateDistance(flight.departureLatitude, flight.departureLongitude, flight.arrivalAirportLatitude, flight.arrivalAirportLongitude), 0)));
const totalDistanceMiles = computed(() => Math.ceil(totalDistance.value * 0.621371192));
const averageDistance = computed(() => Math.ceil(totalDistance.value / Math.max(totalFlights.value, 1)));

const timesAroundEarth = computed(() => (totalDistance.value / earthCircumference));
const timesToMoon = computed(() => (totalDistance.value / distanceToMoon));
const timesToMars = computed(() => (totalDistance.value / distanceToMars));

const totalTime = computed(() => {
    const totalSeconds = statistics.value?.reduce((acc, flight) => acc + (flight.arrivalTime - flight.departureTime) / 1000, 0);

    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds - days * 86400) / 3600);
    const minutes = Math.floor((totalSeconds - days * 86400 - hours * 3600) / 60);
    return { days, hours, minutes };
});

const totalAirports = computed(() => {
    const visited = [];
    statistics.value?.forEach((stat) => {
        if (!visited.includes(stat.departureAirportId)) {
            visited.push(stat.departureAirportId);
        }
        if (!visited.includes(stat.arrivalAirportId)) {
            visited.push(stat.arrivalAirportId);
        }
    });
    return visited.length;
});

const totalAirlines = computed(() => {
    const airlines = [];
    statistics.value?.forEach((stat) => {
        if (!airlines.includes(stat.airlineId)) {
            airlines.push(stat.airlineId);
        }
    });
    return airlines.length;
});

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};
</script>

<template>
    <admin-card v-if="authStore.user && statistics" class="col-span-12">
        <h1 class="text-center text-2xl font-medium mb-3">Welcome back {{ authStore.user.firstName }}!</h1>
        <p class="text-center text-lg font-light">You have currently <span class="text-blue-600 font-medium">{{ totalUpcomingFlights }}</span> scheduled flights.</p>
    </admin-card>
    <admin-card :padding="false" class="col-span-12 md:col-span-6 2xl:col-span-4 h-64 overflow-hidden">
        <STPassportComponent :totalFlights="totalFlights" :totalDistance="totalDistance" :total-time="totalTime"
                             :totalAirports="totalAirports" :totalAirlines="totalAirlines" :issued="new Date()" />
    </admin-card>
    <admin-card :padding="false" class="col-span-12 md:col-span-6 2xl:col-span-4 h-64 overflow-hidden">
        <div v-if="shortestFlight && longestFlight" class="h-full py-3 px-6 grid grid-rows-[1fr_1px_1fr] items-center gap-y-3">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="uppercase text-sm text-gray-700">Shortest flight distance</div>
                    <div class="font-semibold text-xl">{{ shortestFlight.distance }} km</div>
                </div>

                <div class="grid grid-cols-12 gap-x-3 w-full items-center">
                    <div class="col-span-2">
                        <img :src="`${API_URL}/airline/logo?airlineId=${shortestFlight.plane.airlineId}`"
                             :alt="shortestFlight.plane.airline"
                             class="w-10 h-10 object-contain"
                             @error="handleImageError"
                        />
                    </div>
                    <div class="col-span-6">
                        <div class="flex gap-x-3 items-center -mb-1">
                            <div class="text-lg font-medium">{{ shortestFlight.departureAirport.iata }}</div>
                            <font-awesome-icon :icon="faCircleArrowRight" />
                            <div class="text-lg font-medium">{{ shortestFlight.arrivalAirport.iata }}</div>
                        </div>
                        <div class="font-light text-gray-700 ellipsified">{{ shortestFlight.departureAirport.city }} to {{ shortestFlight.arrivalAirport.city }}</div>
                    </div>
                    <div class="col-span-4 text-right">
                        <div class="text-lg font-medium -mb-1">
                            {{ new Date(shortestFlight.departureTime).toLocaleDateString('cs-CZ') }}
                        </div>
                        <div class="font-light text-gray-700">{{ shortestFlight.plane.name }}</div>
                    </div>
                </div>
            </div>

            <div class="h-full bg-gray-200"></div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <div class="uppercase text-sm text-gray-700">Longest flight distance</div>
                    <div class="font-semibold text-xl">{{ longestFlight.distance }} km</div>
                </div>

                <div class="grid grid-cols-12 gap-x-3 w-full items-center">
                    <div class="col-span-2">
                        <img :src="`${API_URL}/airline/logo?airlineId=${longestFlight.plane.airlineId}`"
                             :alt="longestFlight.plane.airline"
                             class="w-10 h-10 object-contain"
                             @error="handleImageError"
                        />
                    </div>
                    <div class="col-span-6">
                        <div class="flex gap-x-3 items-center -mb-1">
                            <div class="text-lg font-medium">{{ longestFlight.departureAirport.iata }}</div>
                            <font-awesome-icon :icon="faCircleArrowRight" />
                            <div class="text-lg font-medium">{{ longestFlight.arrivalAirport.iata }}</div>
                        </div>
                        <div class="font-light text-gray-700 ellipsified">{{ longestFlight.departureAirport.city }} to {{ longestFlight.arrivalAirport.city }}</div>
                    </div>
                    <div class="col-span-4 text-right">
                        <div class="text-lg font-medium -mb-1">
                            {{ new Date(longestFlight.departureTime).toLocaleDateString('cs-CZ') }}
                        </div>
                        <div class="font-light text-gray-700">{{ longestFlight.plane.name }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex h-full items-center justify-center">
            <div class="text-center">
                <h4 class="text-xl font-medium">You haven't book any flight yet.</h4>
                <p class="text-gray-700">Book your first flight and start tracking your statistics.</p>
            </div>
        </div>
    </admin-card>
    <admin-card :padding="false" class="col-span-12 md:col-span-6 2xl:col-span-4 h-64 overflow-hidden">
        <div class="h-full py-3 px-6">
            <div class="mb-4">
                <div class="font-medium">Flight Distance</div>
                <div class="font-semibold text-3xl">
                    {{ totalDistance }} <span class="text-gray-700 font-normal">km</span>
                </div>
                <div class="text-gray-700 text-sm -mb-1">{{ totalDistanceMiles }} mi</div>
                <div class="text-sm">Average distance: {{ averageDistance }} km</div>
            </div>

            <div class="space-y-2">
                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" :style="{ width: `${timesAroundEarth * 100}%`}"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="../../assets/images/space-elements/earth.svg" alt="Earth" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">{{ timesAroundEarth.toFixed(1) }}x <span class="font-light">around Earth</span></div>
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" :style="{ width: `${timesToMoon * 100}%`}"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="../../assets/images/space-elements/moon.svg" alt="Moon" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">{{ timesToMoon.toFixed(2) }}x <span class="font-light">to the Moon</span></div>
                </div>

                <div class="relative w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                    <div class="bg-purple-400 h-8" :style="{ width: `${timesToMars * 100}%`}"></div>

                    <img class="absolute top-0 h-full p-1 rounded-full" src="../../assets/images/space-elements/mars.svg" alt="Mars" />

                    <div class="absolute bottom-1/2 right-4 translate-y-1/2 text-sm">{{ timesToMars.toFixed(5) }}x <span class="font-light">to Mars</span></div>
                </div>
            </div>
        </div>
    </admin-card>
</template>
