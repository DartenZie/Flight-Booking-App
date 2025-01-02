<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPlaneDeparture} from "@fortawesome/free-solid-svg-icons";
import {Reservation} from "@/models/reservation.model";
import {computed} from "vue";
import {useAuthStore} from "@/store/auth.store";

const props = defineProps<{
    reservation: Reservation
}>();

const authStore = useAuthStore();

const airlineName = computed(() => props.reservation.flight.plane.airline);

const departureIata = computed(() => props.reservation.flight.departureAirport.iata);
const departureCity = computed(() => props.reservation.flight.departureAirport.city);
const departureDate = computed(() => date(props.reservation.flight.departureTime));
const departureDateShort = computed(() => dateShort(props.reservation.flight.departureTime));
const departureTime = computed(() => time(props.reservation.flight.departureTime));

const arrivalIata = computed(() => props.reservation.flight.arrivalAirport.iata);
const arrivalCity = computed(() => props.reservation.flight.arrivalAirport.city);
const arrivalDate = computed(() => date(props.reservation.flight.arrivalTime));
const arrivalTime = computed(() => time(props.reservation.flight.arrivalTime));

const boardingTime = computed(() => time(
    new Date(props.reservation.flight.departureTime.valueOf() - 60000 * 30) // 30 minutes before
));

const passengerName = computed(() => `${authStore.user.firstName} ${authStore.user.lastName}`);

const date = (date: Date) => {
    if (!(date instanceof Date) || isNaN(date)) {
        throw new Error("Invalid Date object");
    }
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
};

const dateShort = (date: Date) => {
    if (!(date instanceof Date) || isNaN(date)) {
        throw new Error("Invalid Date object");
    }
    const options = { month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options).toUpperCase();
};

const time = (date: Date) => {
    if (!(date instanceof Date) || isNaN(date)) {
        throw new Error("Invalid Date object");
    }
    const options = { hour: '2-digit', minute: '2-digit', hour12: true };
    return date.toLocaleTimeString('en-US', options);
};

</script>

<template>
    <div class="boarding-pass grid md:grid-cols-[2fr_1fr] grid-rows-[4rem_16rem]">
        <!-- TOP Background -->
        <div class="col-start-1 md:col-end-3 row-start-1 row-end-1 bg-blue-600"></div>

        <div class="hidden md:flex col-start-1 col-end-2 row-start-1 row-end-1 justify-center items-center">
            <h4 class="text-white font-normal text-xl uppercase tracking-widest">Boarding Pass</h4>
        </div>

        <div class="col-start-1 md:col-start-2 md:col-end-2 row-start-1 row-end-1 flex justify-center items-center">
            <h4 class="text-white font-light text-lg uppercase tracking-wide">{{ airlineName }}</h4>
        </div>

        <div class="hidden md:block relative col-start-1 col-end-2 row-start-1 row-span-2">
            <!-- Divider -->
            <div class="absolute w-0.5 h-full right-0 bg-vertical-dashed bg-right bg-[length:0.5rem_2rem]"></div>
        </div>

        <div class="col-start-1 col-end-2 row-start-2 row-span-1 bg-world bg-contain bg-center bg-no-repeat">
            <div class="px-8 py-3 h-full grid grid-cols-2 md:grid-cols-[2fr_3fr_2fr] lg:grid-cols-[2fr_3fr_2fr] grid-rows-[3fr_1fr] gap-x-8 gap-y-4">
                <div class="col-span-1 overflow-hidden">
                    <div class="text-gray-700 text-md font-medium uppercase mb-1.5">From:</div>
                    <div class="text-blue-700 text-4xl font-bold">{{ departureIata }}</div>
                    <div class="text-blue-700 text-sm font-light uppercase text-nowrap overflow-hidden overflow-ellipsis mb-3">
                        {{ departureCity }}
                    </div>
                    <div class="text-gray-700 font-light -mb-1">{{ departureDate }}</div>
                    <div class="text-gray-700 font-light">{{ departureTime }}</div>
                </div>
                <div class="hidden md:flex col-span-1 justify-center items-center">
                    <font-awesome-icon class="text-blue-600 text-6xl opacity-55" :icon="faPlaneDeparture" />
                </div>
                <div class="col-span-1 overflow-hidden">
                    <div class="text-gray-700 text-md font-medium uppercase mb-1.5">To:</div>
                    <div class="text-blue-700 text-4xl font-bold">{{ arrivalIata }}</div>
                    <div class="text-blue-700 text-sm font-light uppercase text-nowrap overflow-hidden overflow-ellipsis mb-3">
                        {{ arrivalCity }}
                    </div>
                    <div class="text-gray-700 font-light -mb-1">{{ arrivalDate }}</div>
                    <div class="text-gray-700 font-light">{{ arrivalTime }}</div>
                </div>

                <div class="col-start-1 col-span-2 md:col-span-3 row-start-2 border-t-2 border-slate-400">
                    <div class="grid grid-cols-4 md:grid-cols-5 gap-x-3 pt-3">
                        <div class="hidden md:block col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Passenger</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ passengerName }}</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Flight</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">--</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Terminal</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">--</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Gate</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">--</div>
                        </div>
                        <div class="col-span-1">
                            <div class="text-gray-600 text-xs uppercase">Seat</div>
                            <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">
                                {{ reservation.seat }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-start-1 md:col-start-2 md:col-span-1 px-8 py-3 h-full">
            <div class="grid grid-rows-[1fr_1fr_2fr] gap-y-3 h-full">
                <div class="row-span-1 border-b-2 border-slate-400 flex justify-between">
                    <div class="max-w-1/2">
                        <div class="text-gray-600 text-xs uppercase">Passenger</div>
                        <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ passengerName }}</div>
                    </div>
                    <div class="max-w-1/2">
                        <div class="text-gray-600 text-xs uppercase">Class</div>
                        <div class="text-blue-700 text-md font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ reservation.class }}</div>
                    </div>
                </div>
                <div class="row-span-1 border-b-2 border-slate-400 grid grid-cols-3 gap-x-3 items-center">
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Date</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ departureDateShort }}</div>
                    </div>
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Boarding</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ boardingTime }}</div>
                    </div>
                    <div class="col-span-1">
                        <div class="text-gray-600 text-xs uppercase">Depart</div>
                        <div class="text-blue-700 text-xs font-medium text-nowrap overflow-hidden overflow-ellipsis">{{ departureTime }}</div>
                    </div>
                </div>
                <div class="row-span-1">
                    <img src="../assets/images/barcode.gif" alt="ABC-abc-1234" class="mt-3">
                </div>
            </div>
        </div>
    </div>
</template>
