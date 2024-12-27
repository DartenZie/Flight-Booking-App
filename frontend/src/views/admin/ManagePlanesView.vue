<script setup lang="ts">
import AdminCard from "@/components/admin/AdminCard.vue";
import {faChevronDown, faSearch} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {onMounted, ref} from "vue";
import {Plane, PlanesResponse} from "@/models/plane.model";
import {useAuthenticatedFetch} from "@/utils/authenticated-fetch";
import FloatingUiDropdown from "@/components/floating-ui/FloatingUiDropdown.vue";
import {useRouter} from "vue-router";

const router = useRouter();

const airlineId = router.currentRoute.value.params.airlineId;

const planes = ref<ReadonlyArray<Plane>>(Plane);

onMounted(async () => {
    const { data } = await useAuthenticatedFetch<PlanesResponse>(`http://localhost:8080/plane?airline_id=${airlineId}`).get().json();
    planes.value = data.value.planes.map((plane) => Plane.parsePlane(plane));
});

const handleSelect = (key: string, planeId: number) => {
    switch (key) {
    case 'manageSeatingConfiguration':
        // manageFlightPricesDialog.reveal({ flight: flights.value.find((flight) => flight.id === flightId) });
        break;
    case 'removePlane':
        // confirmFlightCancel.reveal({ flightId: flightId });
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

                <button class="relative block ms-auto me-8 btn-primary h-12" v-floating-ui-trigger="{ componentId: `edit-plane-${plane.id}` }">
                    <span class="me-7">Edit</span>
                    <font-awesome-icon :icon="faChevronDown" />

                    <floating-ui-dropdown :component-id="`edit-plane-${plane.id}`" position="right" @select="(key) => handleSelect(key, plane.id)" :dropdown-items="[
                        { name: 'manageSeatingConfiguration', value: 'Manage seating configuration' },
                        { name: 'removePlane', value: 'Remove plane' }
                    ]" />
                </button>
            </div>
        </div>
    </admin-card>
</template>
