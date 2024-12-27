<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {onMounted, ref} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {AirlinesResponse} from "@/models/airline.model";
import {createConfirmDialog} from "vuejs-confirm-dialog";
import AddAirlineModal from "@/components/modals/AddAirlineModal.vue";

const createAirlineDialog = createConfirmDialog(AddAirlineModal, {});

const airlines = ref<AirlinesResponse>(null);

onMounted(() => {
    loadAirlines();
});

const loadAirlines = async () => {
    const { data } = await useAuthenticatedFetch<AirlinesResponse>('http://localhost:8080/airline').get().json();
    airlines.value = data.value;
};

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};

createAirlineDialog.onConfirm(async (body: { name: string }) => {
    const response = await useAuthenticatedFetch('http://localhost:8080/airline').post(body).json();
    if (response.statusCode.value === 200) {
        await loadAirlines();
    }
    createAirlineDialog.close();
});
createAirlineDialog.onCancel(createAirlineDialog.close);
</script>

<template>
    <admin-card v-if="airlines" class="col-span-12">
        <div class="flex justify-between">
            <div>
                <h1 class="text-3xl font-semibold mb-3">Manage Airlines</h1>
                <div class="mb-10">Found total of <span class="font-bold">{{ airlines.total }}</span> airlines</div>
            </div>

            <div>
                <button class="btn-primary h-14" @click="createAirlineDialog.reveal()">Add Airline</button>
            </div>
        </div>


        <table class="w-full">
            <thead class="border-b border-t border-[#D6DDE1] h-14">
                <tr>
                    <th class="w-20"></th>
                    <th class="text-left text-gray-500 font-normal">Name</th>
                    <th class="text-left text-gray-500 font-normal w-48">Actions</th>
                </tr>
            </thead>
            <tr class="h-4"></tr>
            <tbody>
                <tr v-for="airline in airlines.airlines" :key="airline.id" class="h-16">
                    <td>
                        <img :src="`http://localhost:8080/airline/logo?airlineId=${airline.id}`"
                             :alt="airline.name"
                             class="w-12 h-12 object-contain"
                             @error="handleImageError"
                        />
                    </td>
                    <td>
                        <div>{{ airline.name }}</div>
                    </td>
                    <td>
                        <div class="inline-block">
                            <router-link :to="`/airline/${airline.id}`" class="btn-primary h-12">Manage airline</router-link>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </admin-card>
</template>
