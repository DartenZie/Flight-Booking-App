<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faDashboard,
    faCalendar,
    faUserGear,
    faRightFromBracket,
    faPlane,
    faUsers, faBars, faTimes
} from "@fortawesome/free-solid-svg-icons";
import {useAuthStore} from "@/store/auth.store";
import {useRouter} from "vue-router";
import {computed, ref} from "vue";
import {SidebarLink} from "@/types/sidebar-link.type";
import SidebarLinkComponent from "@/components/admin/SidebarLinkComponent.vue";

const authStore = useAuthStore();
const router = useRouter();

const sidebarLinks: ReadonlyArray<SidebarLink> = [
    { to: '/profile/dashboard', icon: faDashboard, label: 'Dashboard' },
    { to: '/profile/reservations', icon: faCalendar, label: 'Reservations' },
    { to: '/profile/settings', icon: faUserGear, label: 'Profile Settings' },
    { to: '/profile/manage-airlines', icon: faPlane, label: 'Manage airlines', permissionLevel: 2 },
    { to: '/profile/manage-users', icon: faUsers, label: 'Manage users', permissionLevel: 3 },
];

const filteredSidebarLinks = computed<ReadonlyArray<SidebarLink>>(() => {
    return sidebarLinks.filter((link) => !link.permissionLevel || authStore.user?.permissionLevel >= link.permissionLevel);
});

const showMobileSidebar = ref(false);
router.beforeEach(() => {
    showMobileSidebar.value = false;
});

function onLogout(): void {
    authStore.logout();
    router.push('/sign-in');
}
</script>

<template>
    <div class="h-screen bg-[#B5C2CA] overflow-y-auto xl:overflow-y-hidden">
        <div class="xl:grid xl:grid-cols-admin gap-x-6 h-full ps-4 xl:ps-8">
            <div :class="[
                'xl:flex absolute z-20 top-0 left-0 w-full h-full xl:relative xl:w-96 xl:h-auto flex-col justify-between p-10 bg-[#F0F3F4] xl:border-[#D6DDE1] xl:border-5 xl:rounded-3xl xl:shadow-md xl:my-8',
                showMobileSidebar ? 'flex' : 'hidden'
            ]">
                <div>
                    <div class="flex justify-between items-center mb-10">
                        <router-link to="/profile/dashboard" class="block">
                            <span class="text-xl">
                                Sky<span class="font-semibold">Trip</span>
                            </span>
                        </router-link>

                        <button class="xl:hidden" @click="showMobileSidebar = false">
                            <font-awesome-icon :icon="faTimes" class="text-lg" />
                        </button>
                    </div>

                    <sidebar-link-component v-for="link in filteredSidebarLinks" :key="link.to"
                                            :to="link.to" :icon="link.icon" :label="link.label"
                                            @click="showMobileSidebar = false" />

                    <hr class="mb-10" />

                    <button class="admin-link px-8 flex items-center gap-x-4" @click="onLogout()">
                        <font-awesome-icon :icon="faRightFromBracket" class="w-4" />
                        <span>Logout</span>
                    </button>
                </div>

                <div v-if="authStore.user" class="flex flex-col items-center">
                    <div class="bg-white h-20 w-20 rounded-full border-3 border-white mb-6">
                        <img src="@/assets/images/profile_female_placeholder.svg" alt="Profile" class="w-full h-full object-cover rounded-full" />
                    </div>
                    <div class="font-medium text-xl mb-1">{{ authStore.user.firstName + ' ' + authStore.user.lastName }}</div>
                    <div class="font-normal text-md text-gray-500">{{ authStore.user.email }}</div>
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

