<script setup lang="ts">
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faDashboard,
    faCalendar,
    faUserGear,
    faRightFromBracket,
    faPlane,
    faUsers
} from "@fortawesome/free-solid-svg-icons";
import {useAuthStore, User} from "@/store/auth.store";
import {useRouter} from "vue-router";
import {onMounted, ref} from "vue";

const user = ref<User>(null);

const auth = useAuthStore();
const router = useRouter();

function handleLogout() {
    auth.logout();
    router.push('/sign-in');
}

onMounted(async () => {
    user.value = await auth.user();
});
</script>

<template>
    <div class="h-screen bg-[#B5C2CA]">
        <div class="grid grid-cols-admin gap-x-6 h-full ps-8">
            <div class="w-96 flex flex-col justify-between p-10 bg-[#F0F3F4] border-[#D6DDE1] border-5 rounded-3xl shadow-md my-8">
                <div>
                    <router-link to="/profile/dashboard" class="block mb-10">
                        <span class="text-xl">
                            Sky<span class="font-semibold">Trip</span>
                        </span>
                    </router-link>

                    <hr class="mb-10" />

                    <router-link to="/profile/dashboard" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faDashboard" class="w-4" />
                            <div>Dashboard</div>
                        </div>
                    </router-link>

                    <router-link to="/profile/reservations" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faCalendar" class="w-4" />
                            <div>Reservations</div>
                        </div>
                    </router-link>

                    <router-link to="/profile/settings" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faUserGear" class="w-4" />
                            <div>Profile Settings</div>
                        </div>
                    </router-link>

                    <hr class="mb-2" />

                    <router-link v-if="user && user.permissionLevel >= 2" to="/profile/manage-airlines" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faPlane" class="w-4" />
                            <div>Manage airlines</div>
                        </div>
                    </router-link>

                    <router-link v-if="user && user.permissionLevel >= 3" to="/profile/manage-users" active-class="admin-link-active" class="admin-link">
                        <div class="px-8 flex h-full items-center gap-x-4">
                            <font-awesome-icon :icon="faUsers" class="w-4" />
                            <div>Manage users</div>
                        </div>
                    </router-link>

                    <hr class="mb-10" />

                    <button class="admin-link px-8 flex items-center gap-x-4" @click="handleLogout()">
                        <font-awesome-icon :icon="faRightFromBracket" class="w-4" />
                        <span>Logout</span>
                    </button>
                </div>

                <div v-if="user" class="flex flex-col items-center">
                    <div class="bg-white h-20 w-20 rounded-full border-3 border-white mb-6">
                        <img src="@/assets/images/profile_female_placeholder.svg" alt="Profile" class="w-full h-full object-cover rounded-full" />
                    </div>
                    <div class="font-medium text-xl mb-1">{{ user.firstName + ' ' + user.lastName }}</div>
                    <div class="font-normal text-md text-gray-500">{{ user.email }}</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 grid-rows-min gap-6 py-8 pe-8 overflow-auto">
                <slot />
            </div>
        </div>
    </div>
</template>

