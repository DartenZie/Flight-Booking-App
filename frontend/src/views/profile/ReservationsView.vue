<script setup lang="ts">
import {createConfirmDialog} from "vuejs-confirm-dialog";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faClock,
    faLocationDot,
    faChevronDown,
    faPlaneDeparture,
    faPlaneArrival, faPen
} from "@fortawesome/free-solid-svg-icons";

import AdminCard from "@/components/admin/AdminCard.vue";
import FloatingUiDropdown from "@/components/floating-ui/FloatingUiDropdown.vue";
import ConfirmFlightCancelModal from "@/components/modals/ConfirmFlightCancelModal.vue";

import BoardingTicketModal from "@/components/modals/BoardingTicketModal.vue";
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

const handleSelect = (key: string, reservationId: number) => {
    switch (key) {
    case 'view':
        const reservation = reservations.value.find((r) => r.id === reservationId);
        boardingTicket.reveal({ reservation });
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
    <admin-card class="hidden lg:flex col-span-12 h-28 justify-between">

        <router-link to="/" class="ms-auto btn-primary">Book flight</router-link>
    </admin-card>

    <admin-card class="col-span-12">
        <h2 class="text-2xl font-semibold mb-2">Bookings</h2>
        <p class="text-md text-gray-700 mb-6">See your scheduled flights in the calendar view.</p>

        <div class="lg:hidden mb-6 flex justify-end">
            <router-link to="/" class="btn-primary h-14">Book flight</router-link>
        </div>

        <div class="grid grid-cols-1 gap-y-2 pb-24">

            <div v-for="reservation in reservations" :key="reservation.id" class="border border-gray-200 rounded-2xl md:h-20 grid grid-cols-12 md:flex md:items-center md:gap-x-10">
                <div class="col-span-3 text-center md:w-20 md:min-w-20 text-gray-700 border-b border-e md:border-b-0 border-gray-200">
                    <div class="text-lg font-normal">{{  month(reservation.flight) }}</div>
                    <div class="text-2xl font-bold">{{ reservation.flight.departureTime.getDate() }}</div>
                </div>

                <div class="col-span-6 flex flex-col justify-center border-b border-e md:border-b-0 md:border-e-0 border-gray-200 md:block">
                    <div class="px-3 md:px-0 flex gap-x-2 items-center mb-1">
                        <font-awesome-icon :icon="faClock" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm inline-block w-24">
                            {{ FlightResult.formatTime(reservation.flight.departureTime) }} -
                            {{ FlightResult.formatTime(reservation.flight.arrivalTime) }}
                        </span>
                    </div>
                    <div class="px-3 md:px-0 flex gap-x-2 items-center">
                        <font-awesome-icon :icon="faLocationDot" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">
                            {{ reservation.flight.departureAirport.iata }} -
                            {{ reservation.flight.arrivalAirport.iata }}
                        </span>
                    </div>
                </div>

                <div class="col-span-3 border-b md:border-b-0 border-gray-200 md:hidden flex justify-center items-center">
                    <button class="block relative mx-auto btn-text h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${reservation.id}` }">
                        <font-awesome-icon :icon="faPen" />

                        <floating-ui-dropdown :component-id="`edit-flight-${reservation.id}`" position="right" @select="(key) => handleSelect(key, reservation.id)" :dropdown-items="[
                            { name: 'view', value: 'View boarding ticket' },
                            { name: 'checkIn', value: 'Online check-in' },
                            { name: 'cancelFlight', value: 'Cancel flight' },
                        ]" />
                    </button>
                </div>

                <div class="col-span-12">
                    <div class="hidden 2xl:block text-sm mb-1">
                        {{ duration(reservation.flight) }} flight from
                        <span class="font-semibold">{{ reservation.flight.departureAirport.name }}</span> to
                        <span class="font-semibold">{{ reservation.flight.arrivalAirport.name }}</span>
                    </div>
                    <div class="hidden md:block 2xl:hidden text-sm mb-1">
                        {{ duration(reservation.flight) }} flight from
                        <span class="font-semibold">{{ reservation.flight.departureAirport.iata }}</span> to
                        <span class="font-semibold">{{ reservation.flight.arrivalAirport.iata }}</span>
                    </div>

                    <div class="md:hidden text-sm mb-2 px-6 mt-2">
                        <div class="flex items-center gap-x-6 mb-1">
                            <font-awesome-icon :icon="faPlaneDeparture" class="text-gray-700" />
                            <div class="font-medium">{{ reservation.flight.departureAirport.name }}</div>
                        </div>
                        <div class="flex items-center gap-x-6">
                            <font-awesome-icon :icon="faPlaneArrival" class="text-gray-700" />
                            <div class="font-medium">{{ reservation.flight.arrivalAirport.name }}</div>
                        </div>
                    </div>

                    <div class="flex gap-x-2 items-center px-6 mb-4 md:mb-0 md:px-0">
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

                <button class="hidden md:block relative ms-auto me-8 btn-primary h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${reservation.id}` }">
                    <span class="me-7">Edit</span>
                    <font-awesome-icon :icon="faChevronDown" />

                    <floating-ui-dropdown :component-id="`edit-flight-${reservation.id}`" position="right" @select="(key) => handleSelect(key, reservation.id)" :dropdown-items="[
                        { name: 'view', value: 'View boarding ticket' },
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
