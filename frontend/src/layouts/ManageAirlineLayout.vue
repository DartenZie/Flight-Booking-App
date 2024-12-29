<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPlane, faPlaneDeparture, faCog, faArrowLeft} from "@fortawesome/free-solid-svg-icons";
import {useAirlineStore} from "@/store/airline.store";

const airlineStore = useAirlineStore();

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};
</script>

<template>
    <div v-if="airlineStore.airline" class="h-screen bg-[#B5C2CA]">
        <div class="grid grid-cols-admin gap-x-6 h-full ps-8">
            <div class="w-96 flex flex-col justify-between p-10 bg-[#F0F3F4] border-[#D6DDE1] border-5 rounded-3xl shadow-md my-8">

                <div>
                    <router-link :to="`/airline/${airlineStore.airline.id}`" class="block mb-10">
                        <span class="text-xl">
                            Sky<span class="font-semibold">Trip</span>
                        </span>
                    </router-link>

                    <hr class="mb-10" />

                    <router-link :to="`/airline/${airlineStore.airline.id}/manage-planes`" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faPlane" class="w-4" />
                            <div>Manage Planes</div>
                        </div>
                    </router-link>

                    <router-link :to="`/airline/${airlineStore.airline.id}/manage-flights`" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faPlaneDeparture" class="w-4" />
                            <div>Manage Flights</div>
                        </div>
                    </router-link>

                    <router-link :to="`/airline/${airlineStore.airline.id}/preferences`" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faCog" class="w-4" />
                            <div>Preferences</div>
                        </div>
                    </router-link>

                    <hr class="mb-10" />

                    <router-link to="/profile/manage-airlines" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faArrowLeft" class="w-4" />
                            <div>Back</div>
                        </div>
                    </router-link>
                </div>

                <div v-if="airlineStore.airline" class="flex flex-col items-center">
                    <div class="h-20 w-20 mb-6">
                        <img :src="airlineStore.airline.logoUrl"
                             :alt="airlineStore.airline.name"
                             class="w-full h-full object-contain"
                             @error="handleImageError"
                        />
                    </div>
                    <div class="font-medium text-xl mb-1">{{ airlineStore.airline.name }}</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 grid-rows-min gap-6 py-8 pe-8 overflow-auto">
                <slot />
            </div>
        </div>
    </div>
</template>
