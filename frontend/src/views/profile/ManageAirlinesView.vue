<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {onMounted, ref} from "vue";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {AirlinesResponse} from "@/models/airline.model";
import {createConfirmDialog} from "vuejs-confirm-dialog";
import AddAirlineModal from "@/components/modals/AddAirlineModal.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPen} from "@fortawesome/free-solid-svg-icons";

const API_URL = process.env.VITE_API_URL;

const createAirlineDialog = createConfirmDialog(AddAirlineModal, {});

const airlines = ref<AirlinesResponse>(null);

onMounted(() => {
    loadAirlines();
});

const loadAirlines = async () => {
    const { data } = await useAuthenticatedFetch<AirlinesResponse>(`${API_URL}/airline/list`).get().json();
    airlines.value = data.value;
};

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};

createAirlineDialog.onConfirm(async (body: { name: string }) => {
    const response = await useAuthenticatedFetch(`${API_URL}/airline`).post(body).json();
    if (response.statusCode.value === 201) {
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

            <div class="hidden md:block">
                <button class="btn-primary h-14" @click="createAirlineDialog.reveal()">Add Airline</button>
            </div>
        </div>

        <div class="lg:hidden mb-6">
            <button class="ms-auto btn-primary h-14" @click="createAirlineDialog.reveal()">Add Airline</button>
        </div>

        <div class="w-full overflow-y-auto">
            <table class="min-w-full w-max">
                <thead class="border-b border-t border-[#D6DDE1] h-14">
                    <tr>
                        <th class="w-14 lg:w-20"></th>
                        <th class="text-left text-gray-500 font-normal">Name</th>
                        <th class="text-right lg:text-left text-gray-500 font-normal lg:w-48">Actions</th>
                    </tr>
                </thead>
                <tr class="h-4"></tr>
                <tbody>
                    <tr v-for="airline in airlines.airlines" :key="airline.id" class="h-16">
                        <td class="pe-2">
                            <img :src="`${API_URL}/airline/logo?airlineId=${airline.id}`"
                                 :alt="airline.name"
                                 class="size-8 lg:size-12 object-contain"
                                 @error="handleImageError"
                            />
                        </td>
                        <td class="px-2">
                            <div>{{ airline.name }}</div>
                        </td>
                        <td class="ps-2">
                            <div class="flex justify-end lg:inline-block">
                                <router-link :to="`/airline/${airline.id}`" class="hidden lg:flex btn-primary h-12">Manage airline</router-link>
                                <router-link :to="`/airline/${airline.id}`" class="lg:hidden justify-end btn-text h-12">
                                    <font-awesome-icon :icon="faPen" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </admin-card>
</template>
