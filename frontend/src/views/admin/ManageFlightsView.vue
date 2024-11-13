<script setup lang="ts">
import {createConfirmDialog} from "vuejs-confirm-dialog";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faClock, faLocationDot, faChevronDown, faSearch} from "@fortawesome/free-solid-svg-icons";

import AdminCard from "@/components/admin/AdminCard.vue";
import FloatingUiDropdown from "@/components/floating-ui/FloatingUiDropdown.vue";
import ConfirmFlightCancelModal from "@/components/modals/ConfirmFlightCancelModal.vue";
import ManageFlightPricesModal from "@/components/modals/ManageFlightPricesModal.vue";

const manageFlightPricesCancel = createConfirmDialog(ManageFlightPricesModal, {});
const confirmFlightCancel = createConfirmDialog(ConfirmFlightCancelModal, {});

manageFlightPricesCancel.onConfirm(() => {
    // Todo change flight tickets
    console.log('Flight ticket prices changed');
    manageFlightPricesCancel.close();
});
manageFlightPricesCancel.onCancel(manageFlightPricesCancel.close);

confirmFlightCancel.onConfirm(() => {
    // Todo cancel flight
    console.log('Flight was canceled');
    confirmFlightCancel.close();
});
confirmFlightCancel.onCancel(confirmFlightCancel.close);

const handleSelect = (key: string) => {
    switch (key) {
    case 'manageTickets':
        manageFlightPricesCancel.reveal();
        break;
    case 'cancelFlight':
        confirmFlightCancel.reveal();
        break;
    }
};
</script>

<template>
    <admin-card class="col-span-12 h-28 flex justify-between">
        <div class="w-full max-w-lg h-14 relative">
            <input placeholder="Search"
                   class="bg-[#F0F3F4] w-full h-full rounded-[1.5rem] border-none focus:ring-0 ps-8 pe-14" />
            <font-awesome-icon :icon="faSearch" class="absolute bottom-1/2 translate-y-1/2 right-8" />
        </div>

        <router-link to="/admin/manage-flights/schedule" class="btn-primary">Schedule flight</router-link>
    </admin-card>

    <admin-card class="col-span-12">
        <h2 class="text-2xl font-semibold mb-2">Scheduled flights</h2>
        <p class="text-md text-gray-700 mb-6">See your scheduled flights in the calendar view.</p>

        <div class="grid grid-cols-1 gap-y-2">

            <div class="bg-gray-100 h-14 mb-4 w-min flex gap-x-2 items-center px-2 rounded-lg">
                <button class="h-10 btn-filter active">Upcoming</button>
                <button class="h-10 btn-filter">Past</button>
                <button class="h-10 btn-filter">Cancelled</button>
            </div>

            <div v-for="index in 10" :key="index" class="border border-gray-200 rounded-2xl h-20 flex items-center gap-x-10">
                <div class="text-center w-20 text-gray-700 border-e border-gray-200">
                    <div class="text-lg font-normal">Wed</div>
                    <div class="text-2xl font-bold">28</div>
                </div>

                <div>
                    <div class="flex gap-x-2 items-center mb-1">
                        <font-awesome-icon :icon="faClock" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">09:20 - 10:40</span>
                    </div>
                    <div class="flex gap-x-2 items-center">
                        <font-awesome-icon :icon="faLocationDot" class="w-6 text-gray-600 text-xs" />
                        <span class="text-gray-700 font-light text-sm">PRG - CPH</span>
                    </div>
                </div>

                <div>
                    <div class="text-sm mb-1">1h 20m flight from <span class="font-semibold">Letiště Václava Havla Praha</span> to <span class="font-semibold">Københavns Lufthavn, Kastrup</span></div>
                    <div class="text-xs text-gray-700 font-light">Boeing 737-800 &#183; 213 Seats &#183; 47% full</div>
                </div>

                <button class="relative block ms-auto me-8 btn-primary h-12" v-floating-ui-trigger="{ componentId: `edit-flight-${index}` }">
                    <span class="me-7">Edit</span>
                    <font-awesome-icon :icon="faChevronDown" />

                    <floating-ui-dropdown :component-id="`edit-flight-${index}`" position="right" @select="(key) => handleSelect(key, index)" :dropdown-items="[
                        { name: 'manageTickets', value: 'Manage ticket prices' },
                        { name: 'cancelFlight', value: 'Cancel flight' },
                    ]" />
                </button>
            </div>
        </div>
    </admin-card>
</template>
