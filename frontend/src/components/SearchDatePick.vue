<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCalendarDays } from '@fortawesome/free-solid-svg-icons';
import {computed, onMounted, ref} from "vue";
import {useFloatingUiStore} from "@/store/floating-ui.store";

const floatingUIStore = useFloatingUiStore();

defineProps<{
    id: string,
    type: 'oneWayTrip' | 'roundTrip',
}>();

const date = ref<[Date, Date]>();

const isOpen = computed(() => floatingUIStore.isOpen('search-datepicker'));

const departureDate = computed(() => formatDate(date.value?.[0]));
const returnDate = computed(() => formatDate(date.value?.[1]));

onMounted(() => {
    const startDate = new Date();
    const endDate = new Date(new Date().setDate(startDate.getDate() + 7));
    date.value = [startDate, endDate];
});

const formatDate = (date: Date): string => {
    if (!date) {
        return '';
    }

    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // Get day of week, date, and month
    const dayOfWeek = daysOfWeek[date.getUTCDay()];
    const dayOfMonth = date.getUTCDate();
    const month = months[date.getUTCMonth()];

    // Format the date into the desired string
    return `${dayOfWeek}, ${dayOfMonth} ${month}`;
};

const handleBack = (calendar: 'departure' | 'return'): void => {
    if (calendar === 'departure') {
        date.value[0] = new Date(date.value[0].setDate(date.value[0].getDate() - 1));
    } else  {
        if (date.value[0] >= date.value[1]) {
            return;
        }
        date.value[1] = new Date(date.value[1].setDate(date.value[1].getDate() - 1));
    }
};

const handleNext = (calendar: 'departure' | 'return'): void => {
    if (calendar === 'departure') {
        if (date.value[0] >= date.value[1]) {
            return;
        }
        date.value[0] = new Date(date.value[0].setDate(date.value[0].getDate() + 1));
    } else  {
        date.value[1] = new Date(date.value[1].setDate(date.value[1].getDate() + 1));
    }
};
</script>

<template>
    <div class="relative select-none">
        <div class="border border-slate-200 rounded-lg px-6 h-24 flex gap-x-10">
            <div class="h-full w-full relative">
                <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">Departure</label>
                <input :id="id" type="text" :value="departureDate" readonly
                       v-floating-ui-trigger="{ componentId: 'search-datepicker', triggerEvent: 'focus' }"
                       class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl bottom-1/2 translate-y-1/2 w-full pe-7 ps-0" />
                <div class="flex gap-x-6 absolute bottom-1.5">
                    <button class="btn-text text-xs" @click="handleBack('departure')">Prev</button>
                    <button class="btn-text text-xs" @click="handleNext('departure')">Next</button>
                </div>

                <font-awesome-icon :icon="faCalendarDays" class="pointer-events-none absolute bottom-1/2 translate-y-1/2 text-2xl right-0" />
            </div>
            <div v-if="type === 'roundTrip'" class="h-full w-full relative">
                <label :for="id" class="absolute block uppercase font-normal text-xs top-2.5">Return</label>
                <input :id="id" type="text" :value="returnDate" readonly
                       v-floating-ui-trigger="{ componentId: 'search-datepicker', triggerEvent: 'focus' }"
                       class="absolute block outline-none border-none focus:ring-0 font-bold text-2xl bottom-1/2 translate-y-1/2 w-full pe-7 ps-0" />
                <div class="flex gap-x-6 absolute bottom-1.5">
                    <button class="btn-text text-xs" @click="handleBack('return')">Prev</button>
                    <button class="btn-text text-xs" @click="handleNext('return')">Next</button>
                </div>

                <font-awesome-icon :icon="faCalendarDays" class="pointer-events-none absolute bottom-1/2 translate-y-1/2 text-2xl right-0" />
            </div>
        </div>

        <div v-if="isOpen" class="absolute translate-y-1 z-10 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" tabindex="-1">
            <div v-if="type === 'roundTrip'">
                <date-picker v-model="date" :min-date="new Date()" :enable-time-picker="false" range multi-calendars inline auto-apply />
            </div>
            <div v-else>
                <date-picker v-model="date[0]" :min-date="new Date()" :enable-time-picker="false" inline auto-apply />
            </div>
        </div>
    </div>
</template>
