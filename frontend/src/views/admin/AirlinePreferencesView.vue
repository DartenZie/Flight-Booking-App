<script setup lang="ts">

import AdminCard from "@/components/admin/AdminCard.vue";
import {useAirlineStore} from "@/store/airline.store";
import {ref} from "vue";
import {faChevronRight} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import LogoDropzone from "@/components/LogoDropzone.vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";

const airlineStore = useAirlineStore();

const airlineName = ref<string>(airlineStore.airline.name);

const handleSubmit = async () => {
    if (airlineName.value === "" || airlineName.value === airlineStore.airline.name) {
        return;
    }

    const body = {
        id: airlineStore.airline.id,
        name: airlineName.value
    };

    const response = await useAuthenticatedFetch('http://localhost:8080/airline').put(body).json();
    if (response.statusCode.value === 200) {
        airlineStore.invalidateCache();
    }
};
</script>

<template>
    <admin-card class="col-span-12">
        <form class="space-y-8" @submit.prevent="handleSubmit()">
            <div>
                <h2 class="text-2xl font-semibold mb-2">Airline settings</h2>
                <p class="mt-1 text-sm/6 text-gray-600">Fill in the information in your settings.</p>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
                <div class="max-w-3xl">
                    <div class="flex">
                        <div class="w-1/2">
                            <label for="airlineName" class="block text-sm font-medium mb-2">Airline name</label>
                            <input type="text" id="airlineName" v-model="airlineName" class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" placeholder="AbcAir" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="btn-primary h-10" type="submit">
                    <span class="me-7">Save</span>
                    <font-awesome-icon :icon="faChevronRight" />
                </button>
            </div>
        </form>
    </admin-card>

    <admin-card class="col-span-12 h-96">
        <div>
            <h2 class="text-2xl font-semibold mb-2">Airline logo</h2>
            <p class="mt-1 text-sm/6 text-gray-600">Upload the airline logo to update.</p>
        </div>

        <div class="pb-12">
            <logo-dropzone />
        </div>
    </admin-card>
</template>
