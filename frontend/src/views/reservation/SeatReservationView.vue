<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faPlane, faChevronRight, faUser, faTimes } from '@fortawesome/free-solid-svg-icons';
import PlaneCabinsVisualization from "@/components/PlaneCabinsVisualization.vue";
import ReservationFlightCard from "@/components/ReservationFlightCard.vue";
import {useReservationStore} from "@/store/reservation.store";
import {useAuthStore} from "@/store/auth.store";
import {ref, watch} from "vue";
import {Flight} from "@/models/flight.model";
import HeroSection from "@/components/HeroSection.vue";

const authStore = useAuthStore();
const reservationStore = useReservationStore();

const currentFlight = ref<Flight>(null);
const selectedSeat = ref<string>('');

watch(
    () => reservationStore.departureFlight,
    () => currentFlight.value = reservationStore.departureFlight,
    { immediate: true }
);

const handleSubmit = () => {
    if (reservationStore.returnFlightId == currentFlight.value.id) {
        reservationStore.returnSeat = selectedSeat.value;
    } else {
        reservationStore.departureSeat = selectedSeat.value;
    }

    if (reservationStore.returnFlight && currentFlight.value.id === reservationStore.returnFlight.id) {
        selectedSeat.value = '';
        currentFlight.value = reservationStore.returnFlight;
        return;
    }

    reservationStore.makeReservations();
};
</script>

<template>
    <hero-section v-if="reservationStore.departureFlight" :city="reservationStore.departureFlight.arrivalAirport.city">
        <h2 class="font-medium text-lg mb-8">
            <font-awesome-icon :icon="faPlane" />
            <span class="ms-3">Flight information</span>
        </h2>

        <reservation-flight-card :flight="reservationStore.departureFlight" />
        <reservation-flight-card v-if="reservationStore.returnFlight" :flight="reservationStore.returnFlight" />

        <router-link
            class="btn-primary flex w-min h-12 ms-auto lg:absolute lg:bottom-0 lg:right-10 lg:h-16 lg:w-56 lg:translate-y-1/2 text-left"
            to="/"
        >
            <span class="me-7 lg:me-0">Change Flights</span>
            <font-awesome-icon
                :icon="faArrowLeft"
                class="lg:absolute lg:right-6 lg:bottom-1/2 lg:translate-y-1/2"
            />
        </router-link>
    </hero-section>

    <div class="container px-4 md:px-8 lg:px-20 xl:px-0 my-12 lg:mt-0 xl:mb-24 mx-auto">
        <form @submit.prevent="handleSubmit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 lg:flex lg:justify-center">
                    <div>
                        <div class="mb-14">
                            <h2 class="text-base/7 font-semibold text-gray-900">Select your seats</h2>
                            <p class="mt-1 text-sm/6 text-gray-600">Choose your preferred seats for the best travel experience.</p>
                        </div>

                        <div class="lg:flex lg:gap-x-20 lg:h-160">
                            <div class="lg:w-96 h-full flex flex-col justify-between">
                                <div class="mt-4 mb-4 lg:mb-0">
                                    <h4 class="text-slate-500 font-medium text-sm">Flight #1</h4>
                                    <div v-if="currentFlight" class="text-slate-950 font-bold text-md mb-8">
                                        {{ currentFlight.departureAirport.city }} ({{ currentFlight.departureAirport.iata }}) -
                                        {{ currentFlight.arrivalAirport.city }} ({{ currentFlight.arrivalAirport.iata }})
                                    </div>

                                    <div class="border border-gray-200 h-12 rounded-2xl flex items-center justify-between px-6 mb-4">
                                        <div class="flex gap-x-4 items-center">
                                            <font-awesome-icon :icon="faUser" class="text-purple-700" />
                                            <div v-if="authStore.user" class="font-medium text-md text-gray-500">{{ authStore.user.firstName }} {{ authStore.user.lastName }}</div>
                                        </div>
                                        <div class="flex gap-x-4">
                                            <div class="font-medium text-sm text-gray-500">Seat</div>
                                            <div class="font-medium text-sm w-6">{{ selectedSeat || '--' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="currentFlight" class="mb-4 flex flex-col gap-y-3">
                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-gray-400">
                                                <font-awesome-icon :icon="faTimes" class="text-white" />
                                            </div>
                                            <div class="font-medium text-md">Not available</div>
                                        </div>

                                        <div><!-- --></div>
                                    </div>

                                    <div class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div class="w-10 h-10 rounded flex items-center justify-center bg-cyan-400">
                                                <font-awesome-icon :icon="faUser" class="text-white" />
                                            </div>
                                            <div class="font-medium text-md">Selected</div>
                                        </div>

                                        <div><!-- --></div>
                                    </div>

                                    <div v-for="cabin in currentFlight.plane.seatingConfiguration.cabins" :key="cabin.id" class="flex justify-between">
                                        <div class="flex gap-x-4 items-center">
                                            <div v-if="cabin.className.toLowerCase().includes('business')" class="seat cursor-default bg-yellow-400"></div>
                                            <div v-else-if="cabin.className.toLowerCase().includes('economy')" class="seat cursor-default bg-blue-400"></div>
                                            <div v-else class="seat cursor-default bg-green-200"></div>
                                            <div class="font-medium text-md">{{ cabin.className }} seat</div>
                                        </div>

                                        <div class="flex items-center font-medium text-gray-600">{{ currentFlight.prices.get(cabin.className) }} €</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-16 lg:mt-0 h-160 lg:h-auto">
                                <plane-cabins-visualization v-if="currentFlight" @select="selectedSeat = $event"
                                                            :seating-model="currentFlight.plane.seatingConfiguration" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button v-if="reservationStore.returnFlight && currentFlight.id === reservationStore.returnFlight.id"
                            class="btn-primary h-12" type="submit">
                        <span class="me-7">Continue</span>
                        <font-awesome-icon :icon="faChevronRight" />
                    </button>
                    <button class="btn-primary h-12" type="submit">
                        <span class="me-7">Submit Reservation</span>
                        <font-awesome-icon :icon="faChevronRight" />
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
