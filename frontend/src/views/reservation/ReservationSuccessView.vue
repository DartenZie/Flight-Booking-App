<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowLeft, faPlane } from '@fortawesome/free-solid-svg-icons';
import {useReservationStore} from "@/store/reservation.store";
import ReservationFlightCard from "@/components/ReservationFlightCard.vue";
import HeroSection from "@/components/HeroSection.vue";
import {onMounted, ref} from "vue";
import {Flight} from "@/models/flight.model";

const reservationStore = useReservationStore();

const departureFlight = ref<Flight>(null);
const returnFlight = ref<Flight>(null);

onMounted(() => {
    departureFlight.value = reservationStore.departureFlight;
    returnFlight.value = reservationStore.returnFlight;
});
</script>

<template>
    <hero-section v-if="reservationStore.departureFlight" :city="reservationStore.departureFlight.arrivalAirport.city" :reservation-success="true">
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
            <span class="me-7 lg:me-0">Book another journey</span>
            <font-awesome-icon
                :icon="faArrowLeft"
                class="lg:absolute lg:right-6 lg:bottom-1/2 lg:translate-y-1/2"
            />
        </router-link>
    </hero-section>

</template>
