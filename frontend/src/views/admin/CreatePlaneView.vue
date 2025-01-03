<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import PlaneCabinsVisualization from "@/components/PlaneCabinsVisualization.vue";
import {computed, ref} from "vue";
import {Plane, SeatingModel} from "@/models/plane.model";
import FormControl from "@/components/FormControl.vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {useAirlineStore} from "@/store/airline.store";

const API_URL = process.env.VITE_API_URL;

const airlineStore = useAirlineStore();

const name = ref<string>('');
const seatingConfigurationStr = ref<string>('[Economy 10 3x3]');

const seatingModel = computed<SeatingModel>(() => {
    if (!seatingConfigurationStr.value || !Plane.validateSeatingConfig(seatingConfigurationStr.value)) {
        return null;
    }

    return Plane.parsePlaneSeatingConfiguration(seatingConfigurationStr.value);
});

const submit = async (): Promise<void> => {
    if (!name.value || !seatingConfigurationStr.value || !Plane.validateSeatingConfig(seatingConfigurationStr.value)) {
        return;
    }

    const response = await useAuthenticatedFetch(`${API_URL}/plane`).post({
        airlineId: airlineStore.airline.id,
        name: name.value,
        configuration: seatingConfigurationStr.value,
    }).json();

    if (response.statusCode.value !== 200) {
        console.error(response.error.value);
    }
};
</script>

<template>
    <admin-card class="col-span-6 row-span-2">
        <form @submit.prevent="submit">
            <div class="mb-6">
                <form-control id="airplaneName" v-model="name" label="Airplane name" type="text" />
            </div>
            <div class="mb-6">
                <form-control id="airplaneConfiguration" v-model="seatingConfigurationStr" label="Seating configuration" type="text" />
            </div>

            <div class="flex justify-end items-center">
                <button class="btn-primary h-12" type="submit">
                    <span>Create</span>
                </button>
            </div>
        </form>
    </admin-card>

    <admin-card class="col-span-6 row-span-2">
        <div class="relative h-full min-h-96">
            <div class="absolute w-full h-full">
                <div v-if="seatingModel" class="flex h-full justify-center items-center">
                    <plane-cabins-visualization :seating-model="seatingModel" />
                </div>
            </div>
        </div>
    </admin-card>
</template>
