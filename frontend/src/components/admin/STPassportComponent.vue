<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPassport} from "@fortawesome/free-solid-svg-icons";
import {computed} from "vue";
import {useAuthStore} from "@/store/auth.store";

const authStore = useAuthStore();

defineProps<{
    totalFlights: number;
    totalDistance: number;
    totalTime: { days: number; hours: number; minutes: number };
    totalAirports: number;
    totalAirlines: number;
    issued: Date;
}>();

const formatedDate = computed(() => {
    if (!authStore.user) {
        return '';
    }
    const options: Intl.DateTimeFormatOptions = {day: '2-digit', month: 'short', year: '2-digit'};
    return new Intl.DateTimeFormat('en-GB', options).format(authStore.user.createdAt).replaceAll(' ', '');
});
</script>

<template>
    <div class="relative h-full py-3 px-6 bg-[#081225]">
        <div class="mb-4">
            <div class="text-xl text-white uppercase">My <strong>SkyTrip</strong> Passport</div>
            <div class="text-gray-300 font-light text-xs">
                <font-awesome-icon :icon="faPassport" class="me-3" />
                <span class="uppercase">Passport <strong>&#183;</strong> Pass <strong>&#183;</strong> Pasaporte</span>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-3">
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flights</div>
                <div class="text-2xl text-white font-medium">{{ totalFlights }}</div>
            </div>
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flight Distance</div>
                <div class="text-2xl text-white font-medium">{{ totalDistance }} <span class="font-light">km</span></div>
            </div>
            <div class="col-span-2">
                <div class="text-purple-300 uppercase font-light">Flight Time</div>
                <div class="text-2xl text-white font-medium">
                    <span v-if="totalTime.days > 0">{{ totalTime.days }} <span class="font-light">d</span>&nbsp;</span>
                    <span v-if="totalTime.hours > 0">{{ totalTime.hours }} <span class="font-light">h</span>&nbsp;</span>
                    <span>{{ totalTime.minutes }} <span class="font-light">m</span></span>
                </div>
            </div>
            <div class="col-span-1">
                <div class="text-purple-300 uppercase font-light">Airports</div>
                <div class="text-2xl text-white font-medium">{{ totalAirports }}</div>
            </div>
            <div class="col-span-1">
                <div class="text-purple-300 uppercase font-light">Airlines</div>
                <div class="text-2xl text-white font-medium">{{ totalAirlines }}</div>
            </div>
        </div>

        <div class="absolute bottom-4 text-slate-500 text-sm uppercase">Issued {{ formatedDate }}</div>
    </div>
</template>
