<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faPlane,
    faPlaneDeparture,
    faCog,
    faArrowLeft,
    faTimes,
    faBars
} from "@fortawesome/free-solid-svg-icons";
import {useAirlineStore} from "@/store/airline.store";
import {ref} from "vue";
import {useRouter} from "vue-router";

const airlineStore = useAirlineStore();
const router = useRouter();

const showMobileSidebar = ref(false);
router.beforeEach(() => {
    showMobileSidebar.value = false;
});

const handleImageError = (event: Event) => {
    const target = event.target as HTMLElement;
    target.src = '/logo-placeholder.jpg';
};
</script>

<template>
    <div class="h-screen bg-[#B5C2CA] overflow-y-auto xl:overflow-y-hidden">
        <div class="xl:grid xl:grid-cols-admin gap-x-6 h-full ps-4 xl:ps-8">
            <div :class="[
                'xl:flex absolute z-20 top-0 left-0 w-full h-full xl:relative xl:w-96 xl:h-auto flex-col justify-between p-10 bg-[#F0F3F4] xl:border-[#D6DDE1] xl:border-5 xl:rounded-3xl xl:shadow-md xl:my-8',
                showMobileSidebar ? 'flex' : 'hidden'
            ]">
                <div v-if="airlineStore.airline">
                    <div class="flex justify-between items-center mb-10">
                        <router-link :to="`/airline/${airlineStore.airline.id}`" class="block">
                            <span class="text-xl">
                                Sky<span class="font-semibold">Trip</span>
                            </span>
                        </router-link>

                        <button class="xl:hidden" @click="showMobileSidebar = false">
                            <font-awesome-icon :icon="faTimes" class="text-lg" />
                        </button>
                    </div>

                    <router-link :to="`/airline/${airlineStore.airline.id}/manage-planes`" active-class="admin-link-active" class="admin-link" @click="showMobileSidebar = false">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faPlane" class="w-4" />
                            <div>Manage Planes</div>
                        </div>
                    </router-link>

                    <router-link :to="`/airline/${airlineStore.airline.id}/manage-flights`" active-class="admin-link-active" class="admin-link" @click="showMobileSidebar = false">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faPlaneDeparture" class="w-4" />
                            <div>Manage Flights</div>
                        </div>
                    </router-link>

                    <router-link :to="`/airline/${airlineStore.airline.id}/preferences`" active-class="admin-link-active" class="admin-link" @click="showMobileSidebar = false">
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

            <div class="xl:hidden z-10 h-16 px-4 flex justify-between items-center fixed top-0 left-0 w-full bg-[#F0F3F4] shadow-md">
                <router-link to="/profile/dashboard" class="block">
                    <span class="text-xl">
                        Sky<span class="font-semibold">Trip</span>
                    </span>
                </router-link>

                <button @click="showMobileSidebar = true">
                    <font-awesome-icon :icon="faBars" class="text-lg" />
                </button>
            </div>

            <div class="grid xl:grid-cols-12 h-min max-h-screen gap-6 pt-20 pb-4 xl:py-8 pe-4 xl:pe-8 overflow-y-auto">
                <slot />
            </div>
        </div>
    </div>
</template>
