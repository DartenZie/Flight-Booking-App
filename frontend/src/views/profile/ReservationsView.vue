<script setup lang="ts">
import {createConfirmDialog} from "vuejs-confirm-dialog";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faClock, faLocationDot, faChevronDown} from "@fortawesome/free-solid-svg-icons";

import AdminCard from "@/components/admin/AdminCard.vue";
import FloatingUiDropdown from "@/components/floating-ui/FloatingUiDropdown.vue";
import ConfirmFlightCancelModal from "@/components/modals/ConfirmFlightCancelModal.vue";

import router from "@/router";
import BoardingTicketModal from "@/components/modals/BoardingTicketModal.vue";
import OnlineCheckinModal from "@/components/modals/OnlineCheckinModal.vue";
import {ref, watch} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {Reservation, ReservationsResponse} from "@/models/reservation.model";
import {useAuthStore} from "@/store/auth.store";
import {Flight, FlightResult} from "@/models/flight.model";

const API_URL = process.env.VITE_API_URL;

const authStore = useAuthStore();

const reservations = ref<ReadonlyArray<Reservation>>([]);

const confirmFlightCancel = createConfirmDialog(ConfirmFlightCancelModal, {});
const boardingTicket = createConfirmDialog(BoardingTicketModal, {});
const onlineCheckIn = createConfirmDialog(OnlineCheckinModal, {});

const duration = (flight: Flight) => {
    return FlightResult.formatDuration((flight.arrivalTime.valueOf() - flight.departureTime.valueOf()) / 60000);
};

const month = (flight: Flight) => {
    return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][flight.departureTime.getMonth()];
};

watch(
    () => authStore.user,
    () => {
        if (authStore.user) {
            fetchReservations();
        }
    },
    { immediate: true }
);

async function fetchReservations(): Promise<void> {
    const { data } = await useAuthenticatedFetch<ReservationsResponse>(`${API_URL}/reservation/user?id=${authStore.user.id}`).get().json();
    reservations.value = data.value.reservations.map((reservation) => Reservation.parseReservation(reservation));
}

confirmFlightCancel.onConfirm(async ({ id }) => {
    const { statusCode } = await useAuthenticatedFetch(`${API_URL}/reservation`).delete({ id }).json();
    if (statusCode.value !== 200) {
        console.error('Error while cancelling flight.');
    }
    await fetchReservations();
    confirmFlightCancel.close();
});
confirmFlightCancel.onCancel(confirmFlightCancel.close);

boardingTicket.onCancel(boardingTicket.close);

onlineCheckIn.onConfirm(() => {
    // Todo online checkin
    console.log('Flight was checked in');
    onlineCheckIn.close();
});
onlineCheckIn.onCancel(onlineCheckIn.close);

const handleSelect = (key: string, reservationId: number) => {
    switch (key) {
    case 'view':
        const reservation = reservations.value.find((r) => r.id === reservationId);
        boardingTicket.reveal({ reservation });
        break;
    case 'checkIn':
        onlineCheckIn.reveal();
        break;
    case 'cancelFlight':
        confirmFlightCancel.reveal({ id: reservationId });
        break;
    }
};

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};
</script>

<template>
    <admin-card class="col-span-12 h-28 flex justify-between">

        <router-link to="/" class="ms-auto btn-primary">Book flight</router-link>
    </admin-card>

    <admin-card class="col-span-12">
        <h2 class="text-2xl font-semibold mb-2">Bookings</h2>
        <p class="text-md text-gray-700 mb-6">See your scheduled flights in the calendar view.</p>

        <div class="grid grid-cols-1 gap-y-2">

            <div v-for="reservation in reservations" :key="reservation.id" class="border border-gray-200 rounded-2xl h-20 flex items-center gap-x-10">
                <div class="text-center w-20 text-gray-700 border-e border-gray-200">
                    <div class="text-lg font-normal">{{  month(reservation.flight) }}</div>
                    <div class="text-2xl font-bold">{{ reservation.flight.departureTime.getDate() }}</div>
                </div>

                <div>
                    <div class="flex gap-x-2 items-center mb-1">
                        <font-awesome-icon :icon="faClock" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">
                            {{ FlightResult.formatTime(reservation.flight.departureTime) }} -
                            {{ FlightResult.formatTime(reservation.flight.arrivalTime) }}
                        </span>
                    </div>
                    <div class="flex gap-x-2 items-center">
                        <font-awesome-icon :icon="faLocationDot" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">
                            {{ reservation.flight.departureAirport.iata }} -
                            {{ reservation.flight.arrivalAirport.iata }}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-sm mb-1">
                        {{ duration(reservation.flight) }} flight from
                        <span class="font-semibold">{{ reservation.flight.departureAirport.name }}</span> to
                        <span class="font-semibold">{{ reservation.flight.arrivalAirport.name }}</span></div>
                    <div class="flex gap-x-2 items-center">
                        <img :src="`${API_URL}/airline/logo?airlineId=${reservation.flight.plane.airlineId}`"
                             :alt="reservation.flight.plane.airline"
                             class="w-4 h-4 object-contain"
                             @error="handleImageError"
                        />
                        <div class="text-xs text-gray-700 font-light">
                            {{ reservation.flight.plane.airline }} &#183;
                            {{ reservation.flight.plane.name }} &#183;
                            {{ reservation.class }}
                        </div>
                    </div>
                </div>

                <button class="relative block ms-auto me-8 btn-primary h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${reservation.id}` }">
                    <span class="me-7">Edit</span>
                    <font-awesome-icon :icon="faChevronDown" />

                    <floating-ui-dropdown :component-id="`edit-flight-${reservation.id}`" position="right" @select="(key) => handleSelect(key, reservation.id)" :dropdown-items="[
                        { name: 'view', value: 'View boarding ticket' },
                        { name: 'checkIn', value: 'Online check-in' },
                        { name: 'cancelFlight', value: 'Cancel flight' },
                    ]" />
                </button>
            </div>

            <div v-if="!reservations || reservations.length <= 0" class="mt-20 w-full relative flex justify-center">
                <div class="max-w-xl">
                    <img class="mb-10" src="../../assets/illustrations/empty_illustration.svg" alt="Empty illustration" />
                    <h4 class="text-xl text-center">No Reservations Found</h4>
                    <p class="text-sm text-gray-700 text-center">There are currently no reservations.</p>
                </div>
            </div>
        </div>
    </admin-card>
</template>
