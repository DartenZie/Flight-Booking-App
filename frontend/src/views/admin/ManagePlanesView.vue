<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faSearch} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onMounted, ref} from "vue";
import {Plane, PlanesResponse} from "@/models/plane.model";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import {useRouter} from "vue-router";

const API_URL = process.env.VITE_API_URL;

const router = useRouter();

const airlineId = router.currentRoute.value.params.airlineId;

const planes = ref<ReadonlyArray<Plane>>(Plane);

onMounted(async () => {
    await fetchPlanes();
});

const removePlane = async (planeId: number) => {
    const response = await useAuthenticatedFetch(`${API_URL}/plane`).delete({ id: planeId }).json();

    if (response.statusCode.value === 200) {
        await fetchPlanes();
    }
};

const fetchPlanes = async () => {
    const { data } = await useAuthenticatedFetch<PlanesResponse>(`${API_URL}/plane?airline_id=${airlineId}`).get().json();
    planes.value = data.value.planes.map((plane) => Plane.parsePlane(plane));
};
</script>

<template>
    <admin-card class="col-span-12 h-28 flex justify-end">
        <router-link :to="`/airline/${airlineId}/manage-planes/create`" class="btn-primary">Register plane</router-link>
    </admin-card>

    <admin-card class="col-span-12">
        <h2 class="text-2xl font-semibold mb-2">Planes</h2>
        <p class="text-md text-gray-700 mb-6">See all planes registered by your company.</p>

        <div class="grid grid-cols-1 gap-y-2">
            <div v-for="plane of planes" :key="plane.id" class="border border-gray-200 rounded-2xl h-20 flex items-center gap-x-10">
                <div class="ms-6 w-40 text-md font-normal">{{ plane.name }}</div>
                <div class="text-xs text-gray-700 font-light">
                    <span>{{ plane.calculateTotalSeats() }} seats</span>
                    <span v-for="cabin of plane.seatingConfiguration.cabins" :key="cabin.id">
                        &#183;
                        {{ cabin.className }} {{ plane.calculateSeatsPerCabin(cabin) }}
                    </span>
                </div>

                <button class="relative block ms-auto me-8 btn-primary h-12" @click="removePlane(plane.id)">
                    <span>Remove</span>
                </button>
            </div>
        </div>
    </admin-card>
</template>
